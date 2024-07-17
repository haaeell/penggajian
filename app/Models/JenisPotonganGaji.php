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
        'isWajib',
    ];

   
    public function potonganGaji()
    {
        return $this->belongsToMany(PotonganGaji::class, 'karyawan_jenis_potongan_gaji', 'jenis_potongan_gaji_id', 'potongan_gaji_id')
                    ->withPivot('jumlah', 'isWajib') // sesuaikan dengan kolom di tabel pivot
                    ->withTimestamps();
    }

    public function getJumlahAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }
}

