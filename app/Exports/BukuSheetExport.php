<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BukuSheetExport implements FromCollection, WithTitle, WithHeadings,
    WithStyles, WithColumnWidths, WithMapping
{
    protected string $kategori;

    // Warna per kategori
    const COLORS = [
        'Fiksi'     => 'D6E4F0',
        'Non Fiksi' => 'E8F5E9',
        'semua'     => 'EBF5FB',
    ];

    public function __construct(string $kategori)
    {
        $this->kategori = $kategori;
    }

public function collection()
{
    $query = Book::with('row.bookshelf');  // ← Book, bukan Buku

    if ($this->kategori !== 'semua') {
        $query->where('kategori_buku', $this->kategori); // ← kategori_buku
    }

    return $query->orderBy('id')->get();
}

    public function title(): string
    {
        return $this->kategori === 'semua' ? 'Semua' : $this->kategori;
    }

    public function headings(): array
    {
        return ['No', 'Kode Buku', 'Judul', 'Pengarang', 'Tahun Terbit', 'Kategori', 'Status', 'Rak'];
    }

public function map($book): array
{
    static $no = 0;
    $no++;

    // Kolom Kategori
    $kategori = match($book->kategori_buku) {
        'fiksi'    => 'Fiksi',
        'nonfiksi' => 'Non Fiksi',
        default    => '-',
    };

    // Kolom Rak
    $rak = '-';
    if ($book->row && $book->row->bookshelf) {
        $rak = $book->row->bookshelf->no_rak . ' - ' . $book->row->baris_ke;
    } elseif ($book->id_baris) {
        $rak = $book->id_baris;
    }

    return [
        $no,
        $book->kode_buku ?? '-',
        $book->judul,
        $book->pengarang,
        $book->tahun_terbit,
        $kategori,         
        ucfirst($book->status ?? '-'),
        $rak,              
    ];
}

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 12,
            'C' => 38,
            'D' => 26,
            'E' => 14,
            'F' => 13,
            'G' => 13,
            'H' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowColor  = self::COLORS[$this->kategori] ?? 'EBF5FB';
        $lastRow   = $sheet->getHighestRow();
        $lastCol   = 'H';

        // ── Header style ──
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Data rows — warna selang-seling ──
        for ($row = 2; $row <= $lastRow; $row++) {
            $bg = ($row % 2 === 0) ? $rowColor : 'F5F5F5';
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bg],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // ── Border seluruh tabel ──
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'BDBDBD'],
                ],
            ],
        ]);

        // ── Freeze header ──
        $sheet->freezePane('A2');

        return [];
    }
}