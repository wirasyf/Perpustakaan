<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AnggotaDiterimaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected string $kelas;
    private int $rowNumber = 0;


    public function __construct(string $kelas = 'semua') // menerima filter kelas dari controller
    {
        $this->kelas = $kelas;
    }

    public function collection() //mengambil data dari database
    {
        $query = User::where('role', 'anggota')
            ->where('status', 'aktif');

        if ($this->kelas !== 'semua') {
            $query->where('kelas', 'like', $this->kelas . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Username',
            'NIS',
            'Kelas',
            'No. Telepon',
            'Alamat',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($user): array // map() mengubah setipa row data mejadi array untuk excel
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $user->name,
            $user->username,
            (string) ($user->nis ?? '-'),
            $user->kelas ?? '-',
            $user->telephone ?? '-',
            $user->alamat ?? '-',
            ucfirst($user->status),
            $user->created_at->format('d/m/Y'),
        ];
    }

    public function columnWidths(): array //mengatur lebar masing2 kolom
    {
        return [
            'A' => 6,    
            'B' => 25,  
            'C' => 18,   
            'D' => 14, 
            'E' => 12,   
            'F' => 16,   
            'G' => 50,   
            'H' => 12,   
            'I' => 16,   
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = 'I';

        // ── Styling header (baris 1) ──
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'], // teks putih
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'], // biru tua
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Warna baris data selang-seling ──
        for ($row = 2; $row <= $lastRow; $row++) {
            // Baris genap → biru muda, baris ganjil → abu-abu terang
            $bg = ($row % 2 === 0) ? 'DBEAFE' : 'F5F5F5';

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $bg],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
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

        // ── Kolom No & Status rata tengah ──
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:E{$lastRow}")->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ── Freeze baris header supaya tidak ikut scroll ──
        $sheet->freezePane('A2');

        return [];
    }
}
    
