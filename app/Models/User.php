<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
    ];

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    public function getRoleBadgeClassAttribute()
    {
        switch ($this->role) {
            case 'admin':
                return 'badge rounded-pill bg-danger';
            case 'pimpinan':
                return 'badge rounded-pill bg-success';
            case 'karyawan':
                return 'badge rounded-pill bg-primary';
            default:
                return 'badge bg-secondary';
        }
    }

    
}
