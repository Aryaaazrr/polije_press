<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_users';
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $attributes = [
        'id_role' => 2,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->email_verified_at = now();
        });
    }

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_users', 'id_users');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function history()
    {
        return $this->hasMany(History::class, 'id_users', 'id_users');
    }

    public function contributor()
    {
        return $this->hasMany(DetailContributorsBuku::class, 'id_users', 'id_users');
    }
}