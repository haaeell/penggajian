<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $karyawan = Karyawan::with(['user', 'jabatan'])->get();
        $absensi = Absensi::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('karyawan_id');

        return view('absensi.index', compact('karyawan', 'absensi', 'bulan', 'tahun'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $hadir = $request->input('hadir', []);
        $izin = $request->input('izin', []);
        $sakit = $request->input('sakit', []);
        $alpa = $request->input('alpa', []);

        foreach ($request->input('hadir', []) as $karyawan_id => $value) {
            Absensi::updateOrCreate(
                ['karyawan_id' => $karyawan_id, 'bulan' => $bulan, 'tahun' => $tahun],
                [
                    'hadir' => $hadir[$karyawan_id],
                    'izin' => $izin[$karyawan_id] ?? 0,
                    'sakit' => $sakit[$karyawan_id] ?? 0,
                    'alpa' => $alpa[$karyawan_id] ?? 0,
                ]
            );
        }

        return redirect()->route('absensi.index', ['bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', 'Absensi berhasil disimpan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $absensi = Absensi::where('karyawan_id', $id)
            ->where('bulan', request('bulan'))
            ->where('tahun', request('tahun'))
            ->first();

        if ($absensi) {
            $absensi->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new AbsensiImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data absensi berhasil diimpor.');
        } catch (ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return redirect()->back()->with('error', implode('<br>', $errors));
        }
    }
}
