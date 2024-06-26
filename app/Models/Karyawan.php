<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'jabatan_id',
        'nik',
        'tanggal_bergabung',
        'no_hp',
        'alamat',
        'no_rekening',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function potonganGaji()
    {
        return $this->hasMany(PotonganGaji::class, 'karyawan_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
