<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'judul',
        'pengarang',
        'tahun_terbit',
        'kategori_buku',
        'id_baris',
        'cover',
        'deskripsi',
    ];

    public function row()
    {
        return $this->belongsTo(Row::class, 'id_baris');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buku_id');
    }

    /**
     * Get the book cover URL.
     */
    public function getCoverUrlAttribute()
    {
        if (!$this->cover) {
            return asset('img/buku.png');
        }

        return asset('storage/' . $this->cover);
    }

    public function items()
    {
        return $this->hasMany(BookItem::class);
    }

    // Hitung stok dari items yang tersedia
    public function getStokAttribute()
    {
        return $this->items()->where('status', 'tersedia')->count();
    }

}
