<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKategoriBuku extends Model
{
    use HasFactory;
    protected $table = 'detail_kategori_buku';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
