<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\BukuSheetExport;  
use App\Exports\BukuSummarySheet;

class BukuExport implements WithMultipleSheets
{
    protected string $kategori;

    public function __construct(string $kategori = 'semua')
    {
        $this->kategori = $kategori;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->kategori === 'semua' || $this->kategori === 'fiksi') {
            $sheets[] = new BukuSheetExport('fiksi');
        }

        if ($this->kategori === 'semua' || $this->kategori === 'nonfiksi') {
            $sheets[] = new BukuSheetExport('nonfiksi');
        }

        if ($this->kategori === 'semua') {
            $sheets[] = new BukuSheetExport('semua');
        }

        $sheets[] = new BukuSummarySheet($this->kategori);

        return $sheets;
    }
}