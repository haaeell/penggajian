@extends('layouts.dashboard')

@section('content') 
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Karyawan</h3>
        <div class="card shadow border-0"> 
            <div class="col-md-3">
                <button class="btn rounded-pill btn-primary mx-3 mt-3 "><i class="bi bi-plus"></i> Tambah Karyawan </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Tanggal Bergabung</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>Karyawan {{ $i }}</td>
                                    <td>Jabatan {{ $i }}</td>
                                    <td>{{ now()->subDays(rand(0, 365))->format('d-m-Y') }}</td>
                                    <td>08{{ rand(1111111111, 9999999999) }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn rounded-pill btn-warning btn-sm"><i class="bi bi-pencil"></i> </button>
                                            <button class="btn rounded-pill btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
