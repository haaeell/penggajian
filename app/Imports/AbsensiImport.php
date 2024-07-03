<?php
namespace App\Imports;

use App\Models\Absensi;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AbsensiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $karyawan = Karyawan::where('nik', $row['nik'])->first();

        if ($karyawan) {
            return new Absensi([
                'karyawan_id' => $karyawan->id,
                'bulan' => $row['bulan'],
                'tahun' => $row['tahun'],
                'hadir' => $row['hadir'],
                'izin' => $row['izin'],
                'sakit' => $row['sakit'],
                'alpa' => $row['alpa'],
            ]);
        }
    }
}
