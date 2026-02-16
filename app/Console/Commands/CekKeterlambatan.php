<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class CekKeterlambatan extends Command
{
    protected $signature = 'app:cek-keterlambatan';

    protected $description = 'Cek dan update status keterlambatan transaksi';

    public function handle()
    {
        $today = Carbon::today();

        $terlambat = Transaction::where('status', 'belum_dikembalikan')
            ->whereDate('tanggal_jatuh_tempo', '<', $today)
            ->get();

        foreach ($terlambat as $trx) {
            $trx->update([
                'status' => 'terlambat'
            ]);
        }

        $this->info('Cek keterlambatan selesai. Total diperbarui: ' . $terlambat->count());
    }
}
