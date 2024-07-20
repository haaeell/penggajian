<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = ['karyawan_id', 'jumlah', 'status', 'tujuan'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    
}
