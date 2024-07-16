<?php
namespace App\Imports;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class KaryawanImport implements ToModel, WithHeadingRow
{
    /**
     * Convert Excel date to Y-m-d format.
     *
     * @param mixed $excelDate
     * @return string|null
     */
    private function transformExcelDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate))->format('Y-m-d');
        }
        return null; // or handle non-numeric cases as needed
    }

    public function model(array $row)
    {
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make('123123123'),
            ]
        );

        return new Karyawan([
            'user_id' => $user->id,
            'nik' => $row['nik'],
            'jabatan_id' => $row['jabatan_id'],
            'tanggal_bergabung' => $this->transformExcelDate($row['tanggal_bergabung']),
            'no_hp' => $row['no_hp'],
            'no_rekening' => $row['no_rekening'],
            'alamat' => $row['alamat'],
            'tanggal_lahir' => $this->transformExcelDate($row['tanggal_lahir']),
            'tempat_lahir' => $row['tempat_lahir'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}
