<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
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
        return Transaction::with('user', 'book')
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('tanggal_peminjaman', [$this->startDate, $this->endDate]);
            })
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama User',
            'Judul Buku',
            'Tipe',
            'Tanggal Peminjaman',
        ];
    }

    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $transaction->user->name ?? '-',
            $transaction->book->title ?? '-',
            $transaction->type,
            $transaction->tanggal_peminjaman 
                ? \Carbon\Carbon::parse($transaction->tanggal_peminjaman)->format('d/m/Y')
                : '-',
        ];
    }
}
