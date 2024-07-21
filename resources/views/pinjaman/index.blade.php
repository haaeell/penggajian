@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Daftar Pinjaman</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table datatable" >
               @if (Auth::user()->role == 'karyawan')
               <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pinjamanModal">
                Ajukan Pinjaman
            </button>
               @endif
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Karyawan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tujuan</th>
                        @if (Auth::user()->role == 'admin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $loan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loan->karyawan->user->name }}</td>
                            <td>{{ 'Rp ' . number_format($loan->jumlah, 0, ',', '.') }}</td>

                            <td>{{ $loan->status }}</td>
                            <td>{{ $loan->tujuan }}</td>
                          @if (Auth::user()->role == 'admin')
                          <td>
                            @if($loan->status == 'pending')
                                <form action="{{ route('pinjaman.approve', $loan->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('pinjaman.reject', $loan->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </form>
                            @else
                                {{ $loan->status == 'disetujui' ? 'Disetujui' : 'Ditolak' }}
                            @endif
                        </td>
                          @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="pinjamanModal" tabindex="-1" aria-labelledby="pinjamanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinjamanModalLabel">Form Pengajuan Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pinjaman.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Pinjaman</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan</label>
                        <textarea class="form-control" id="tujuan" name="tujuan"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
