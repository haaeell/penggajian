<?php
namespace App\Imports;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    { 
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make('password'), 
            ]
        );

        return new Karyawan([
            'user_id' => $user->id,
            'nik' => $row['nik'],
            'jabatan_id' => $row['jabatan_id'],
            'tanggal_bergabung' => $row['tanggal_bergabung'],
            'no_hp' => $row['no_hp'],
            'no_rekening' => $row['no_rekening'],
            'alamat' => $row['alamat'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'tempat_lahir' => $row['tempat_lahir'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}

