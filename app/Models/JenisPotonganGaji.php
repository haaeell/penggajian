<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPotonganGaji extends Model
{
    use HasFactory;

    protected $table = 'jenis_potongan_gaji';

    protected $fillable = [
        'jenis_potongan',
        'jumlah',
    ];


    // public function karyawan()
    // {
    //     return $this->belongsToMany(Karyawan::class, 'karyawan_jenis_potongan_gaji', 'jenis_potongan_gaji_id', 'karyawan_id')
    //                 ->withPivot('jumlah')
    //                 ->withTimestamps();
    // }
    public function potonganGaji()
    {
        return $this->belongsToMany(PotonganGaji::class, 'karyawan_jenis_potongan_gaji', 'jenis_potongan_gaji_id', 'potongan_gaji_id')
                    ->withTimestamps();
    }
}
