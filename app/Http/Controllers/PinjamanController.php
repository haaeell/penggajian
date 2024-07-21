<?php
namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamanController extends Controller
{
    public function index()
    {
        
    if(Auth::user()->role == "karyawan"){
        $pinjaman = Pinjaman::with('karyawan')->where('karyawan_id', Auth::user()->karyawan->id)->get();
    }
    else{
        $pinjaman = Pinjaman::with('karyawan')->get();
    }
        return view('pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        return view('pinjaman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric',
            'tujuan' => 'nullable|string',
        ]);

        Pinjaman::create([
            'karyawan_id' => Auth::user()->karyawan->id,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
            'tujuan' => $request->tujuan,
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pengajuan pinjaman telah berhasil.');
    }

    public function approve($id)
    {
        $pinjaman = Pinjaman::find($id);
        $pinjaman->status = 'disetujui';
        $pinjaman->save();

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman telah disetujui.');
    }

    public function reject($id)
    {
        $pinjaman = Pinjaman::find($id);
        $pinjaman->status = 'ditolak';
        $pinjaman->save();

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman telah ditolak.');
    }
}
