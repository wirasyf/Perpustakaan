<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected ?string $status;
    protected ?string $startDate;
    protected ?string $endDate;
    protected ?string $kelas;
    private int $rowNumber = 0;

    public function __construct(?string $status = null, ?string $startDate = null, ?string $endDate = null, ?string $kelas = null)
    {
        $this->status    = $status;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->kelas     = $kelas;
    }

    public function collection()
    {
        return Transaction::with('user', 'book')
            ->when($this->status && $this->status !== 'semua', function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('tanggal_peminjaman', [$this->startDate, $this->endDate]);
            })
            ->when($this->kelas && $this->kelas !== 'semua', function ($q) {
                $q->whereHas('user', fn($uq) => $uq->where('kelas', $this->kelas));
            })
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Anggota',
            'Kelas',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Jatuh Tempo',
            'Tanggal Kembali',
            'Status',
        ];
    }

    public function map($t): array
    {
        $this->rowNumber++;

        $status = match($t->status) {
            'belum_dikembalikan'  => 'Sedang Dipinjam',
            'sudah_dikembalikan'  => 'Sudah Dikembalikan',
            'menunggu_konfirmasi' => 'Proses Pengembalian',
            'terlambat'           => 'Terlambat',
            default               => ucfirst($t->status),
        };

        return [
            $this->rowNumber,
            $t->user->name ?? '-',
            $t->user->kelas ?? '-',
            $t->book->judul ?? '-',
            $t->tanggal_peminjaman?->format('d/m/Y') ?? '-',
            $t->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-',
            $t->tanggal_pengembalian?->format('d/m/Y') ?? '-',
            $status,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 25,
            'C' => 14,
            'D' => 35,
            'E' => 16,
            'F' => 20,
            'G' => 16,
            'H' => 22,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header
        $sheet->getStyle('A1:H1')->applyFromArray([
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

        // Warna baris selang-seling
        for ($row = 2; $row <= $lastRow; $row++) {
            $bg = ($row % 2 === 0) ? 'DBEAFE' : 'F5F5F5';
            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bg],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // Border
        $sheet->getStyle("A1:H{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'BDBDBD'],
                ],
            ],
        ]);

        // Rata tengah
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C2:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F2:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G2:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane('A2');

        return [];
    }
}