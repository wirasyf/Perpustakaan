<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Exports\BukuSheetExport; 


class BukuSummarySheet implements FromArray, WithTitle, WithStyles
{
    protected string $kategori;

    public function __construct(string $kategori)
    {
        $this->kategori = $kategori;
    }

    public function title(): string
    {
        return 'Summary';
    }

public function array(): array
{
    $all = Book::all();
    $f   = Book::where('kategori_buku', 'fiksi')->get();    // ← kategori_buku + lowercase
    $nf  = Book::where('kategori_buku', 'nonfiksi')->get(); // ← kategori_buku + lowercase

    return [
        ['Keterangan',          'Semua',                                      'Fiksi',                                   'Non Fiksi'],
        ['Total Buku',           $all->count(),                                $f->count(),                               $nf->count()],
        ['Status: Dipinjam',     $all->where('status','dipinjam')->count(),    $f->where('status','dipinjam')->count(),   $nf->where('status','dipinjam')->count()],
        ['Status: Tersedia',     $all->where('status','tersedia')->count(),    $f->where('status','tersedia')->count(),   $nf->where('status','tersedia')->count()],
        ['Tahun Terbit Tertua',  $all->min('tahun_terbit'),                   $f->min('tahun_terbit'),                   $nf->min('tahun_terbit')],
        ['Tahun Terbit Terbaru', $all->max('tahun_terbit'),                   $f->max('tahun_terbit'),                   $nf->max('tahun_terbit')],
    ];
}
    public function styles(Worksheet $sheet)
    {
        // Header row
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E79']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Data rows warna selang-seling
        $colors = ['FFF9C4', 'D6E4F0', 'E8F5E9', 'FFF9C4', 'D6E4F0'];
        for ($i = 2; $i <= 6; $i++) {
            $bg = $colors[$i - 2];
            $sheet->getStyle("A{$i}:D{$i}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'font' => $i === 2 ? ['bold' => true] : [],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("B{$i}:D{$i}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        // Bold kolom A (label)
        $sheet->getStyle('A2:A6')->getFont()->setBold(true);

        // Border
        $sheet->getStyle('A1:D6')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BDBDBD']]],
        ]);

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(28);
        foreach (['B','C','D'] as $col) {
            $sheet->getColumnDimension($col)->setWidth(15);
        }

        $sheet->freezePane('A2');

        return [];
    }
}