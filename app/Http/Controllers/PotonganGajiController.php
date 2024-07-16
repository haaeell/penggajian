<?php

namespace App\Http\Controllers;

use App\Models\JenisPotonganGaji;
use App\Models\Karyawan;
use App\Models\PotonganGaji;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PotonganGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $bulan = $request->bulan ?? date('n'); 
        $tahun = $request->tahun ?? date('Y'); 
    
        $potonganGajiQuery = PotonganGaji::with(['karyawan.user', 'jenisPotonganGaji'])
            ->when($bulan, function ($query, $bulan) {
                return $query->where('bulan', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                return $query->where('tahun', $tahun);
            })
            ->get();
    
        $karyawan = Karyawan::with('user')->get();
        $jenisPotonganGaji = JenisPotonganGaji::all();
    
        return view('potongan_gaji.index', compact('potonganGajiQuery', 'karyawan', 'jenisPotonganGaji', 'bulan', 'tahun'));
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
        $request->validate([
            'karyawan_id' => 'required', // Pastikan karyawan_id tidak boleh kosong
            'jenis_potongan_gaji_id' => 'required|array',
            'jenis_potongan_gaji_id.*' => 'exists:jenis_potongan_gaji,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
        ]);

        try {
            $potonganGaji = new PotonganGaji();
            $potonganGaji->karyawan_id = $request->karyawan_id;
            $potonganGaji->bulan = $request->bulan;
            $potonganGaji->tahun = $request->tahun;
            $potonganGaji->total_potongan_gaji = 0; // temporary, untuk dihitung nanti

            $potonganGaji->save(); // Simpan objek ke database

            // Simpan jenis potongan gaji terkait
            foreach ($request->jenis_potongan_gaji_id as $jenisId) {
                // Attach jenis potongan gaji ke potongan gaji yang baru disimpan
                $jenisPotongan = JenisPotonganGaji::find($jenisId);
                $potonganGaji->jenisPotonganGaji()->attach($jenisPotongan, ['karyawan_id' => $request->karyawan_id]);
            }

            // Hitung total potongan gaji dan simpan kembali
            $totalPotongan = $potonganGaji->jenisPotonganGaji()->sum('jumlah');
            $potonganGaji->total_potongan_gaji = $totalPotongan;
            $potonganGaji->save();

            return redirect()->route('potongan-gaji.index')->with('success', 'Potongan Gaji berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
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
    public function edit($id)
    {
        $potonganGaji = PotonganGaji::findOrFail($id);
        $karyawan = Karyawan::with('user')->get();
        $jenisPotonganGaji = JenisPotonganTetap::all();

        $selectedJenisPotonganGaji = $potonganGaji->jenisPotonganGaji->pluck('id')->toArray();

        return response()->json([
            'karyawan_id' => $potonganGaji->karyawan_id,
            'jenis_potongan_gaji_id' => $selectedJenisPotonganGaji,
            'jenis_potongan_gaji' => $jenisPotonganGaji->pluck('jenis_potongan', 'id')->toArray(),
            'bulan' => $potonganGaji->bulan,
            'tahun' => $potonganGaji->tahun,
        ]);
    }

    /**
     * Memperbarui potongan gaji yang ada di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required',
            'jenis_potongan_gaji_id' => 'required|array',
            'jenis_potongan_gaji_id.*' => 'exists:jenis_potongan_gaji,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
        ]);

        try {
            $potonganGaji = PotonganTetap::findOrFail($id);
            $potonganGaji->karyawan_id = $request->karyawan_id;
            $potonganGaji->bulan = $request->bulan;
            $potonganGaji->tahun = $request->tahun;
            $potonganGaji->jenisPotonganGaji()->detach();

            foreach ($request->jenis_potongan_gaji_id as $jenisId) {
                $jenisPotongan = JenisPotonganGaji::find($jenisId);
                $potonganGaji->jenisPotonganGaji()->attach($jenisPotongan, ['karyawan_id' => $request->karyawan_id]);
            }

            $totalPotongan = $potonganGaji->jenisPotonganGaji()->sum('jumlah');
            $potonganGaji->total_potongan_gaji = $totalPotongan;
            $potonganGaji->save();

            return redirect()->route('potongan-gaji.index')->with('success', 'Potongan Gaji berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $potonganGaji = PotonganGaji::findOrFail($id);
        $potonganGaji->jenisPotonganGaji()->detach();
        $potonganGaji->delete();

        return redirect()->route('potongan-gaji.index')->with('success', 'Potongan Gaji berhasil dihapus.');
    }
}
