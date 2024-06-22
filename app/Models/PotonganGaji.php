<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganGaji extends Model
{
    use HasFactory;
    protected $table = 'potongan_gaji';

    protected $fillable = [
        'karyawan_id',
        'total_potongan_gaji',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function jenisPotonganGaji()
    {
        return $this->belongsToMany(JenisPotonganGaji::class, 'karyawan_jenis_potongan_gaji', 'potongan_gaji_id', 'jenis_potongan_gaji_id')
                    ->withTimestamps();
    }
}
