<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Karyawan;
use App\Models\Penggajian;
use App\Models\PotonganGaji;
use App\Models\JenisPotonganGaji;
use App\Models\Pinjaman;
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
        $karyawan = Auth::user()->karyawan;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil jenis potongan gaji yang wajib
        $potonganWajib = JenisPotonganGaji::where('isWajib', true)->get();

        // Logika untuk mengambil data gaji karyawan
        if (auth()->user()->role == 'karyawan') {
            $karyawans = Karyawan::with([
                'user',
                'jabatan',
                'absensi' => function ($query) use ($bulan, $tahun) {
                    $query->where('bulan', $bulan)->where('tahun', $tahun);
                }
            ])
                ->where('user_id', auth()->user()->id)
                ->whereHas('absensi', function ($query) use ($bulan, $tahun) {
                    $query->where('bulan', $bulan)->where('tahun', $tahun);
                })
                ->get();
        } else {
            $karyawans = Karyawan::with([
                'user',
                'jabatan',
                'absensi' => function ($query) use ($bulan, $tahun) {
                    $query->where('bulan', $bulan)->where('tahun', $tahun);
                }
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
            $tunjangan_jabatan = $karyawan->jabatan->tunjangan_jabatan;

            $hadir = $karyawan->absensi->sum('hadir');
            $gaji_kotor = ($gaji_per_hari * $hadir) + $tunjangan_transportasi + $uang_makan  + $tunjangan_jabatan;

            // Ambil potongan pinjaman
            $pinjamanPotongan = Pinjaman::where('karyawan_id', $karyawan->id)
                ->where('status', 'disetujui')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();

            $total_potongan_pinjaman = $pinjamanPotongan->sum('jumlah');

            // Gabungkan potongan wajib dan potongan pinjaman
            $detail_potongan_wajib = $potonganWajib->map(function ($potongan) use ($gaji_kotor) {
                return [
                    'jenis_potongan' => $potongan->jenis_potongan,
                    'jumlah' => $potongan->jumlah,
                    'potongan' => ($potongan->jumlah / 100) * $gaji_kotor
                ];
            });

            $total_potongan_wajib = $detail_potongan_wajib->sum('potongan');

            $total_potongan_gaji = $total_potongan_wajib + $total_potongan_pinjaman;

            $gaji_bersih = $gaji_kotor - $total_potongan_gaji;

            $penggajianData[] = [
                'karyawan' => $karyawan,
                'gaji_per_hari' => $gaji_per_hari,
                'tunjangan_transportasi' => $tunjangan_transportasi,
                'uang_makan' => $uang_makan,
                'tunjangan_jabatan' => $tunjangan_jabatan,
                'hadir' => $hadir,
                'gaji_kotor' => $gaji_kotor,
                'total_potongan_wajib' => $total_potongan_wajib,
                'total_potongan_pinjaman' => $total_potongan_pinjaman,
                'total_potongan_gaji' => $total_potongan_gaji,
                'gaji_bersih' => $gaji_bersih,
                'detail_potongan_wajib' => $detail_potongan_wajib,
                'pinjamanPotongan' => $pinjamanPotongan
            ];

            // dd($penggajianData);
        }

        return view('penggajian.index', compact('penggajianData', 'karyawan'));
    }


    public function generatePdf($id)
    {
        $karyawan = Karyawan::with(['user', 'jabatan', 'absensi', 'potonganGaji.jenisPotonganGaji', 'pinjaman'])->findOrFail($id);
        $gaji_per_hari = $karyawan->jabatan->gaji_per_hari;
        $tunjangan_transportasi = $karyawan->jabatan->tunjangan_transportasi;
        $uang_makan = $karyawan->jabatan->uang_makan;
        $tunjangan_jabatan = $karyawan->jabatan->tunjangan_jabatan;
    
        $hadir = $karyawan->absensi->sum('hadir');
        $gaji_kotor = ($gaji_per_hari * $hadir) + $tunjangan_transportasi + $uang_makan + $tunjangan_jabatan;
    
        // Menghitung potongan wajib
        $potonganWajib = JenisPotonganGaji::where('isWajib', true)->get();
        $detail_potongan_wajib = $potonganWajib->map(function ($potongan) use ($gaji_kotor) {
            return [
                'jenis_potongan' => $potongan->jenis_potongan,
                'jumlah' => $potongan->jumlah,
                'potongan' => ($potongan->jumlah / 100) * $gaji_kotor
            ];
        });
        $total_potongan_wajib = $detail_potongan_wajib->sum('potongan');
    
        $total_potongan_gaji = $total_potongan_wajib + $karyawan->potonganGaji->sum('total_potongan_gaji');
        $total_pinjaman = $karyawan->pinjaman->where('status', 'disetujui')->sum('jumlah');
        $rincian_pinjaman = $karyawan->pinjaman->where('status', 'disetujui'); // Get all approved loans
        $gaji_bersih = $gaji_kotor - $total_pinjaman - $total_potongan_gaji;
    
        $data = [
            'karyawan' => $karyawan,
            'gaji_per_hari' => $gaji_per_hari,
            'tunjangan_transportasi' => $tunjangan_transportasi,
            'uang_makan' => $uang_makan,
            'tunjangan_jabatan' => $tunjangan_jabatan,
            'hadir' => $hadir,
            'gaji_kotor' => $gaji_kotor,
            'total_potongan_gaji' => $total_potongan_gaji,
            'total_pinjaman' => $total_pinjaman,
            'gaji_bersih' => $gaji_bersih,
            'tanggal' => now()->locale('id')->isoFormat('D MMMM YYYY'),
            'bulan' => now()->locale('id')->isoFormat('MMMM YYYY'),
            'jenis_potongan' => $karyawan->potonganGaji->flatMap(function ($potongan) {
                return $potongan->jenisPotonganGaji;
            }),
            'detail_potongan_wajib' => $detail_potongan_wajib,
            'rincian_pinjaman' => $rincian_pinjaman
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
    public function show($id, Request $request)
{
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');

    // Ambil data karyawan dengan absensi dan potongan gaji berdasarkan bulan dan tahun
    $karyawan = Karyawan::with([
        'user',
        'jabatan',
        'absensi' => function ($query) use ($bulan, $tahun) {
            $query->where('bulan', $bulan)->where('tahun', $tahun);
        },
        'potonganGaji.jenisPotonganGaji'
    ])->findOrFail($id);

    // Menghitung gaji per hari, tunjangan, dan uang makan
    $gaji_per_hari = $karyawan->jabatan->gaji_per_hari;
    $tunjangan_transportasi = $karyawan->jabatan->tunjangan_transportasi;
    $uang_makan = $karyawan->jabatan->uang_makan;
    $tunjangan_jabatan = $karyawan->jabatan->tunjangan_jabatan;

    // Menghitung jumlah hadir berdasarkan data absensi yang difilter
    $hadir = $karyawan->absensi->sum('hadir');

    // Menghitung gaji kotor
    $gaji_kotor = ($gaji_per_hari * $hadir) + $tunjangan_transportasi + $uang_makan + $tunjangan_jabatan;

    // Menghitung potongan wajib
    $potonganWajib = JenisPotonganGaji::where('isWajib', true)->get();
    $detail_potongan_wajib = $potonganWajib->map(function ($potongan) use ($gaji_kotor) {
        return [
            'jenis_potongan' => $potongan->jenis_potongan,
            'jumlah' => $potongan->jumlah,
            'potongan' => ($potongan->jumlah / 100) * $gaji_kotor
        ];
    });
    $total_potongan_wajib = $detail_potongan_wajib->sum('potongan');

    // Ambil potongan pinjaman yang disetujui
    $pinjamanPotongan = Pinjaman::where('karyawan_id', $karyawan->id)
        ->where('status', 'disetujui')
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->get();

    $total_potongan_pinjaman = $pinjamanPotongan->sum('jumlah');

    // Gabungkan semua potongan
    $total_potongan_gaji = $total_potongan_wajib + $total_potongan_pinjaman;

    // Menghitung gaji bersih
    $gaji_bersih = $gaji_kotor - $total_potongan_gaji;

    return response()->json([
        'karyawan' => $karyawan,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'gaji_per_hari' => $gaji_per_hari,
        'tunjangan_transportasi' => $tunjangan_transportasi,
        'uang_makan' => $uang_makan,
        'tunjangan_jabatan' => $tunjangan_jabatan,
        'hadir' => $hadir,
        'gaji_kotor' => $gaji_kotor,
        'total_potongan_gaji' => $total_potongan_gaji,
        'total_potongan_pinjaman' => $total_potongan_pinjaman,
        'gaji_bersih' => $gaji_bersih,
        'jenis_potongan' => $karyawan->potonganGaji->flatMap(function ($potongan) {
            return $potongan->jenisPotonganGaji;
        }),
        'detail_potongan_wajib' => $detail_potongan_wajib,
        'pinjamanPotongan' => $pinjamanPotongan
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
