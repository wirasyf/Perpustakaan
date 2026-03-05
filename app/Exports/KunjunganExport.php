<?php

namespace App\Exports;

use App\Models\Visit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KunjunganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected ?string $hari;
    protected ?string $bulan;
    protected ?string $tahun;
    protected ?string $kelas;
    private int $rowNumber = 0;

    /**
     * Menerima filter dari controller.
     */
    public function __construct(?string $hari = null, ?string $bulan = null, ?string $tahun = null, ?string $kelas = null)
    {
        $this->hari  = $hari;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kelas = $kelas;
    }

    /**
     * Ambil data kunjungan dari database,
     * filter berdasarkan hari, bulan, atau tahun jika ada.
     */
    public function collection()
    {
        return Visit::with('user')
            ->when($this->hari,  fn($q) => $q->whereDate('tanggal_datang', $this->hari))
            ->when($this->bulan, fn($q) => $q->whereMonth('tanggal_datang', $this->bulan))
            ->when($this->tahun, fn($q) => $q->whereYear('tanggal_datang', $this->tahun))
            ->when($this->kelas && $this->kelas !== 'semua', function($q) {
                $q->whereHas('user', fn($uq) => $uq->where('kelas', $this->kelas));
            })
            ->orderBy('tanggal_datang', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Pengunjung', 'Kelas', 'Tanggal Datang'];
    }

    public function map($visit): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $visit->user->name ?? '-',
            $visit->user->kelas ?? '-',
            $visit->tanggal_datang
                ? \Carbon\Carbon::parse($visit->tanggal_datang)->format('d/m/Y')
                : '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 30,
            'C' => 15,
            'D' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header
        $sheet->getStyle('A1:D1')->applyFromArray([
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
            $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bg],
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // Border
        $sheet->getStyle("A1:D{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'BDBDBD'],
                ],
            ],
        ]);

        // Rata tengah kolom No & Tanggal
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C2:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D2:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane('A2');

        return [];
    }
}