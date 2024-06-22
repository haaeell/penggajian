<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'hadir',
        'izin',
        'sakit',
        'alpa',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
