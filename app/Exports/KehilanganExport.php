<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KehilanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected ?string $status;
    protected ?string $startDate;
    protected ?string $endDate;
    private int $rowNumber = 0;

    public function __construct(?string $status = null, ?string $startDate = null, ?string $endDate = null)
    {
        $this->status    = $status;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        return Report::with(['user', 'transaction.book'])
            ->when($this->status && $this->status !== 'semua', function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Anggota',
            'Kelas',
            'Judul Buku',
            'Keterangan',
            'Tanggal Laporan',
            'Status',
        ];
    }

    public function map($report): array
    {
        $this->rowNumber++;

        $status = match($report->status) {
            'sudah_dikembalikan' => 'Sudah Diganti',
            'belum_dikembalikan' => 'Belum Diganti',
            default              => ucfirst($report->status ?? '-'),
        };

        return [
            $this->rowNumber,
            $report->user->name ?? $report->transaction->user->name ?? '-',
            $report->user->kelas ?? $report->transaction->user->kelas ?? '-',
            $report->transaction->book->judul ?? '-',  // ← title → judul
            $report->keterangan ?? ($report->description ?? '-'),
            $report->created_at
                ? \Carbon\Carbon::parse($report->created_at)->format('d/m/Y')
                : '-',
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
            'E' => 18,
            'F' => 18,
            'G' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header
        $sheet->getStyle('A1:G1')->applyFromArray([
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
            $bg = ($row % 2 === 0) ? 'FEE2E2' : 'F5F5F5'; // merah muda & abu
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bg],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // Border
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
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

        $sheet->freezePane('A2');

        return [];
    }
}