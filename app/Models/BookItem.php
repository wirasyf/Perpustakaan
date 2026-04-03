<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'kode_buku',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buku_id');
    }
}