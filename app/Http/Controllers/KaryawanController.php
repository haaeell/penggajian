<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.index', compact('jabatans'));
    }

    public function getKaryawan()
    {
        $karyawan = Karyawan::select(['id', 'user_id', 'jabatan_id', 'tanggal_bergabung', 'no_hp'])
                    ->with('user','jabatan'); 

        return DataTables::of($karyawan)
                ->addColumn('name', function ($karyawan) {
                    return $karyawan->user->name ?? 'User not found';
                })
                ->addColumn('email', function ($karyawan) {
                    return $karyawan->user->email ?? 'Email not found';
                })
                ->addColumn('jabatan', function ($karyawan) {
                    return $karyawan->jabatan->jabatan ?? 'Jabatan not found';
                })
                ->addColumn('action', function ($karyawan) {
                    return '
                        <button class="btn btn-warning btn-sm btn-edit" data-id="' . $karyawan->id . '"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $karyawan->id . '"><i class="bi bi-trash"></i></button>
                    ';
                })
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'jabatan_id' => 'required',
            'tanggal_bergabung' => 'required|date',
            'no_hp' => 'required|string',
        ]);

        // Create User first if not exists
        $user = User::firstOrCreate([
            'email' => $request->email,
        ], [
            'name' => $request->name,
            'password' => bcrypt('default_password'), // Default password, change as needed
        ]);

        // Create Karyawan
        $karyawan = Karyawan::create([
            'user_id' => $user->id,
            'jabatan_id' => $request->jabatan_id,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'no_hp' => $request->no_hp,
        ]);

        return response()->json(['success' => 'Karyawan added successfully.']);
    }

    public function edit($id)
{
    $karyawan = Karyawan::with('jabatan','user')->findOrFail($id);
    
    $jabatans = Jabatan::all();
    
    return response()->json([
        'karyawan' => $karyawan,
        'jabatans' => $jabatans,
    ]);
}
    public function show($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $jabatans = Jabatan::all();
        return view('karyawan.edit', compact('karyawan', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'jabatan_id' => 'required',
            'tanggal_bergabung' => 'required|date',
            'no_hp' => 'required|string',
        ]);

        $karyawan = Karyawan::findOrFail($id);

        // Update User
        $user = User::findOrFail($karyawan->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Karyawan
        $karyawan->update([
            'jabatan_id' => $request->jabatan_id,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'no_hp' => $request->no_hp,
        ]);

        return response()->json(['success' => 'Karyawan updated successfully.']);
    }

    public function destroy($id)
    {
        try {

            $karyawan = Karyawan::findOrFail($id);

            $user = User::findOrFail($karyawan->user_id);

            DB::beginTransaction();

            $user->delete();

            $karyawan->delete();
            DB::commit();

            return response()->json(['success' => 'Karyawan and associated user deleted successfully.']);
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollback();

            return response()->json(['error' => 'Failed to delete karyawan and associated user.'], 500);
        }
    }
}
