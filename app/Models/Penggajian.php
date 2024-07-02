<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;
    protected $table = 'penggajian';

    protected $fillable = [
        'karyawan_id',
        'potong_gaji_id',
        'bulan',
        'total_penghasilan' ,
        'total_potongan',
        'gaji_bersih',
        'status',
    ];

    /**
     * Get the karyawan associated with the Penggajian
     */
    protected $dates = ['bulan'];
    
    public function getBulanAttribute($value)
    {
        return Carbon::parse($value);
    }
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }


    /**
     * Get the potongan gaji associated with the Penggajian
     */
    public function potonganGaji()
    {
        return $this->belongsTo(PotonganGaji::class, 'potong_gaji_id');
    }

    /**
     * Get the absensi associated with the Penggajian
     */
    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    /**
     * Calculate gaji kotor
     */
    public function calculateGajiKotor()
    {
        $jabatan = $this->karyawan->jabatan;
        $absensi = $this->absensi;

        return ($jabatan->gaji_perhari * $absensi->hadir) + $jabatan->tunjangan_transportasi + $jabatan->uang_makan;
    }

    /**
     * Calculate total potongan
     */
    public function calculateTotalPotongan()
    {
        return $this->potonganGaji->total_potongan_gaji ?? 0;
    }

    /**
     * Calculate gaji bersih
     */
    public function calculateGajiBersih()
    {
        return $this->calculateGajiKotor() - $this->calculateTotalPotongan();
    }
}
