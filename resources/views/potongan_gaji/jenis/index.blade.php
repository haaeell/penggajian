@extends('layouts.dashboard')

@section('content') 
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold"> Jenis Potongan Gaji</h3>
        <div class="card shadow border-0"> 
            <div class="col-md-3">
                <button class="btn rounded-pill btn-primary mx-3 mt-3 "><i class="bi bi-plus"></i> Tambah Jenis Potongan </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Jenis Potongan</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>Jenis Potongan {{ $i }}</td>
                                    <td>Rp.{{rand(100000, 99999)}}</td>
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
