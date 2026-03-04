<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KehilanganExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?string $startDate;
    protected ?string $endDate;
    private int $rowNumber = 0;

    public function __construct(?string $startDate = null, ?string $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        return Report::with(['user', 'transaction.book'])
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
            'Nama User',
            'Judul Buku',
            'Keterangan',
            'Tanggal Laporan',
        ];
    }

    public function map($report): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $report->user->name ?? '-',
            $report->transaction->book->title ?? '-',
            $report->description,
            $report->created_at 
                ? \Carbon\Carbon::parse($report->created_at)->format('d/m/Y')
                : '-',
        ];
    }
}
