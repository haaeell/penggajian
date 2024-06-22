<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::all();
        $users = User::all();
        return view('users.index',compact('jabatans','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getUsers()
    {
        $users = User::select(['id', 'name', 'email', 'role'])
                    ->orderBy('created_at', 'desc'); // Mengurutkan berdasarkan created_at terbaru
    
        return DataTables::of($users)
                ->addColumn('action', function ($user) {
                    return '
                        <button class="btn btn-warning btn-sm btn-edit" data-id="' . $user->id . '"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $user->id . '"><i class="bi bi-trash"></i></button>
                    ';
                })
                ->make(true);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $password = $request->input('password', '123123123');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => $request->role,
        ]);

        if ($request->role === 'karyawan') {
            Karyawan::create([
                'user_id' => $user->id,
                'jabatan_id' => $request->jabatan_id,
                'tanggal_bergabung' => $request->tanggal_bergabung,
                'no_hp' => $request->no_hp,
            ]);
        }

        return response()->json(['success' => 'User saved successfully.']);
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
        $user = User::with('karyawan')->findOrFail($id); 
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Update user
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->role === 'karyawan') {
            $karyawan = Karyawan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'jabatan_id' => $request->jabatan_id,
                    'tanggal_bergabung' => $request->tanggal_bergabung,
                    'no_hp' => $request->no_hp,
                ]
            );
        } else {
            Karyawan::where('user_id', $user->id)->delete();
        }

        return response()->json(['success' => 'User updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        Karyawan::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json(['success' => 'User deleted successfully.']);
    }
}
