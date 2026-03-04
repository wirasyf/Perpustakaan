<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Row;
use App\Models\Bookshelf;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BukuExport; 
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exportExcel(Request $request)
    {
        $kategori = $request->input('kategori', 'semua'); // semua | Fiksi | Non Fiksi

        $namaFile = match($kategori) {
            'fiksi'     => 'data-buku-fiksi.xlsx',
            'nonfiksi' => 'data-buku-non-fiksi.xlsx',
            default     => 'data-buku-semua.xlsx',
        };

        return Excel::download(new BukuExport($kategori), $namaFile);
    }
    
    public function index(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        // Ambil filter dari request + default
        $search = $request->input('search', '');
        $date = $request->input('date', '');
        $filter = $request->input('filter', '');

        // Query Builder (BELUM get)
        $query = Book::with('row.bookshelf');

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%");
            });
        }

        // Filter Tahun Terbit
        if ($date) {
            $query->where('tahun_terbit', $date);
        }

        // Filter Kategori
        if ($filter) {
            $query->where('kategori_buku', $filter);
        }

        // Fetch unique years for filter
        $years = Book::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');

        // Paginate results
        $books = $query->paginate(10);

        return view('admin.kelola_data_buku', compact(
            'books',
            'search',
            'date',
            'filter',
            'years'
        ));
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        $bookshelves = Bookshelf::all();
        return view('admin.CRUD_kelola_buku', [
         'book' => null,
         'rows' => $rows,
         'bookshelves' => $bookshelves,
]);
    }


    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'kode_buku' => 'nullable|string|unique:books,kode_buku',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'required|in:fiksi,nonfiksi',
            'nomor_rak' => 'required|string|max:50',
            'baris_ke' => 'required|integer',
            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'required|string',
        ]);

        // Auto-generate kode_buku if empty
        if (empty($data['kode_buku'])) {
            do {
                $generated = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);
            } while (Book::where('kode_buku', $generated)->exists());
            $data['kode_buku'] = $generated;
        }

        // Find or create Bookshelf
        $bookshelf = Bookshelf::firstOrCreate(
            ['no_rak' => $data['nomor_rak']],
            ['keterangan' => '-']
        );

        // Find or create Row
        $row = Row::firstOrCreate(
            [
                'rak_id' => $bookshelf->id,
                'baris_ke' => $data['baris_ke']
            ],
            ['keterangan' => '-']
        );

        // Assign id_baris
        $data['id_baris'] = $row->id;

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $data['status'] = 'tersedia';

        // Remove helper fields before storage
        unset($data['nomor_rak'], $data['baris_ke']);

        Book::create($data);

        return redirect()->route('books.index')
                         ->with('success', 'Buku berhasil ditambahkan');
    }

    public function show(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $book->load('row.bookshelf'); 
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $book->load('row.bookshelf');
        return view('admin.CRUD_kelola_buku', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'kode_buku' => 'nullable|string|unique:books,kode_buku,' . $book->id,
            'judul' => 'nullable|string|max:255',
            'pengarang' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'nullable|in:fiksi,nonfiksi',
            'nomor_rak' => 'nullable|string|max:50',
            'baris_ke' => 'nullable|integer',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Handle Rack/Row update
        if (!empty($data['nomor_rak']) && !empty($data['baris_ke'])) {
            $bookshelf = Bookshelf::firstOrCreate(
                ['no_rak' => $data['nomor_rak']],
                ['keterangan' => '-']
            );
            $row = Row::firstOrCreate([
                'rak_id' => $bookshelf->id,
                'baris_ke' => $data['baris_ke']
            ], ['keterangan' => '-']);
            $data['id_baris'] = $row->id;
        }

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Clean data
        unset($data['nomor_rak'], $data['baris_ke']);
        $data = array_filter($data, fn($value) => !is_null($value));

        $book->update($data);

        if ($request->wantsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil diupdate',
                'book' => $book
            ]);
        }

        return redirect()->route('books.index') 
                         ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        try {
            $book->delete();
            
            // Return JSON for AJAX requests
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Buku berhasil dihapus'
                ], 200);
            }
            
            return redirect()->route('books.index')
                             ->with('success', 'Buku berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus buku: ' . $e->getMessage()
                ], 400);
            }
            
            return redirect()->route('books.index')
                             ->with('error', 'Gagal menghapus buku');
        }
    }

    public function browse()
    {
        if (Auth::user()?->role !== 'anggota') abort(403);
        $books = Book::where('status', 'tersedia')->with('row')->get();

        // Cek apakah siswa sudah meminjam buku (masih aktif)
        $hasActiveLoan = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])
            ->exists();

        return view('siswa.pinjam-buku', compact('books', 'hasActiveLoan'));
    }
}