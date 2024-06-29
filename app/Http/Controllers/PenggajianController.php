<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Penggajian;
use App\Models\PotonganGaji;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggajianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    if (auth()->user()->role == 'karyawan') {
        // Jika peran pengguna adalah karyawan, hanya ambil data gaji miliknya
        $karyawans = Karyawan::with([
            'user',
            'jabatan',
            'absensi' => function ($query) use ($bulan, $tahun) {
                $query->where('bulan', $bulan)->where('tahun', $tahun);
            },
            'potonganGaji.jenisPotonganGaji'
        ])
        ->where('user_id', auth()->user()->id)
        ->whereHas('absensi', function ($query) use ($bulan, $tahun) {
            $query->where('bulan', $bulan)->where('tahun', $tahun);
        })
        ->get();
    } else {
        // Jika peran pengguna selain karyawan, ambil semua data gaji
        $karyawans = Karyawan::with([
            'user',
            'jabatan',
            'absensi' => function ($query) use ($bulan, $tahun) {
                $query->where('bulan', $bulan)->where('tahun', $tahun);
            },
            'potonganGaji.jenisPotonganGaji'
        ])
        ->whereHas('absensi', function ($query) use ($bulan, $tahun) {
            $query->where('bulan', $bulan)->where('tahun', $tahun);
        })
        ->get();
    }

    $penggajianData = [];

    foreach ($karyawans as $karyawan) {
        $gaji_per_hari = $karyawan->jabatan->gaji_per_hari;
        $tunjangan_transportasi = $karyawan->jabatan->tunjangan_transportasi;
        $uang_makan = $karyawan->jabatan->uang_makan;

        $hadir = $karyawan->absensi->sum('hadir');
        $gaji_kotor = ($gaji_per_hari * $hadir) + $tunjangan_transportasi + $uang_makan;

        $total_potongan_gaji = $karyawan->potonganGaji->sum('total_potongan_gaji');
        $gaji_bersih = $gaji_kotor - $total_potongan_gaji;

        $penggajianData[] = [
            'karyawan' => $karyawan,
            'gaji_per_hari' => $gaji_per_hari,
            'tunjangan_transportasi' => $tunjangan_transportasi,
            'uang_makan' => $uang_makan,
            'hadir' => $hadir,
            'gaji_kotor' => $gaji_kotor,
            'total_potongan_gaji' => $total_potongan_gaji,
            'gaji_bersih' => $gaji_bersih,
        ];
    }

    return view('penggajian.index', compact('penggajianData'));
}



    public function generatePdf($id)
    {
        $karyawan = Karyawan::with(['user', 'jabatan', 'absensi', 'potonganGaji.jenisPotonganGaji'])->findOrFail($id);
        $gaji_per_hari = $karyawan->jabatan->gaji_per_hari;
        $tunjangan_transportasi = $karyawan->jabatan->tunjangan_transportasi;
        $uang_makan = $karyawan->jabatan->uang_makan;

        $hadir = $karyawan->absensi->sum('hadir');
        $gaji_kotor = ($gaji_per_hari * $hadir) + $tunjangan_transportasi + $uang_makan;

        $total_potongan_gaji = $karyawan->potonganGaji->sum('total_potongan_gaji');
        $gaji_bersih = $gaji_kotor - $total_potongan_gaji;

        $data = [
            'karyawan' => $karyawan,
            'gaji_per_hari' => $gaji_per_hari,
            'tunjangan_transportasi' => $tunjangan_transportasi,
            'uang_makan' => $uang_makan,
            'hadir' => $hadir,
            'gaji_kotor' => $gaji_kotor,
            'total_potongan_gaji' => $total_potongan_gaji,
            'gaji_bersih' => $gaji_bersih,
            'tanggal' => now()->locale('id')->isoFormat('D MMMM YYYY'),
            'bulan' => now()->locale('id')->isoFormat('MMMM YYYY'),
            'jenis_potongan' => $karyawan->potonganGaji->flatMap(function ($potongan) {
                return $potongan->jenisPotonganGaji;
            }),
        ];

        $pdf = Pdf::loadView('penggajian.pdf', $data);
        return $pdf->download('slip-gaji-' . $karyawan->nik . '.pdf');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function simpanDataGaji(Request $request)
    {
        $penggajianData = $request->input('penggajianData');

        foreach ($penggajianData as $data) {
            $karyawan = Karyawan::find($data['karyawan']['id']);

            $penggajian = new Penggajian();
            $penggajian->karyawan_id = $karyawan->id;
            $penggajian->bulan = now();
            $penggajian->total_penghasilan = $data['gaji_kotor'];
            $penggajian->total_potongan = $data['total_potongan_gaji'];
            $penggajian->gaji_bersih = $data['gaji_bersih'];
            $penggajian->save();
        }

        return redirect()->back()->with('success', 'Data gaji berhasil disimpan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $karyawan = Karyawan::with(['user', 'jabatan', 'absensi', 'potonganGaji.jenisPotonganGaji'])->findOrFail($id);
        $gaji_per_hari = $karyawan->jabatan->gaji_per_hari;
        $tunjangan_transportasi = $karyawan->jabatan->tunjangan_transportasi;
        $uang_makan = $karyawan->jabatan->uang_makan;

        $hadir = $karyawan->absensi->sum('hadir');
        $gaji_kotor = ($gaji_per_hari * $hadir) + $tunjangan_transportasi + $uang_makan;

        $total_potongan_gaji = $karyawan->potonganGaji->sum('total_potongan_gaji');
        $gaji_bersih = $gaji_kotor - $total_potongan_gaji;

        return response()->json([
            'karyawan' => $karyawan,
            'gaji_per_hari' => $gaji_per_hari,
            'tunjangan_transportasi' => $tunjangan_transportasi,
            'uang_makan' => $uang_makan,
            'hadir' => $hadir,
            'gaji_kotor' => $gaji_kotor,
            'total_potongan_gaji' => $total_potongan_gaji,
            'gaji_bersih' => $gaji_bersih,
            'jenis_potongan' => $karyawan->potonganGaji->flatMap(function ($potongan) {
                return $potongan->jenisPotonganGaji;
            }),
        ]);
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
    public function destroy(string $id)
    {
        //
    }
}
