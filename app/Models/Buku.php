<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function history()
    {
        return $this->hasMany(History::class, 'id_buku', 'id_buku');
    }

    public function detailKategoriBuku()
    {
        return $this->hasMany(DetailKategoriBuku::class, 'id_buku', 'id_buku');
    }

    public function detailContributorBuku()
    {
        return $this->hasMany(DetailContributorsBuku::class, 'id_buku', 'id_buku');
    }
}