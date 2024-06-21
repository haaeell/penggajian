@extends('layouts.dashboard')

@section('content') 
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold"> Penggajian</h3>
        <div class="card shadow border-0"> 
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Gaji Bersih</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>NIK{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>Karyawan {{ $i }}</td>
                                    <td>Jabatan{{ $i }}</td>
                                    <td>Rp.{{rand(100000, 99999)}}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn rounded-pill btn-secondary btn-sm"><i class="bi bi-printer"></i> </button>
                                            <button class="btn rounded-pill btn-primary btn-sm"><i class="bi bi-eye"></i></button>
                                            <button class="btn rounded-pill btn-success btn-sm"><i class="bi bi-check"></i></button>
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
