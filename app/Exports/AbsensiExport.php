<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;

class AbsensiExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Absensi::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Karyawan ID',
            'Bulan',
            'Tahun',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpa',
            'Created At',
            'Updated At',
        ];
    }
}
