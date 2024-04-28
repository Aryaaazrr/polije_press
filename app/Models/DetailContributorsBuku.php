<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailContributorsBuku extends Model
{
    use HasFactory;
    protected $table = 'detail_contributors_buku';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}