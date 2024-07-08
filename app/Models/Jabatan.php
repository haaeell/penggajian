<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan';

    protected $fillable = [
        'jabatan',
        'gaji_per_hari',
        'tunjangan_transportasi',
        'uang_makan',
        'tunjangan_jabatan',
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function getGajiPerHariAttribute($value)
    {
        return number_format($value, 0, '', '');
    }

    public function getTunjanganTransportasiAttribute($value)
    {
        return number_format($value, 0, '', '');
    }

    public function getUangMakanAttribute($value)
    {
        return number_format($value, 0, '', '');
    }
    public function getTunjanganJabatanAttribute($value)
    {
        return number_format($value, 0, '', '');
    }
}
