@extends('layouts.dashboard')

@section('content') <h5 class="pb-1 mb-4">Dashboard</h5>
<div class="row mb-5">
  <div class="col-md">
    <div class="card mb-3">
      <div class="row g-0">
        <div class="col-md-4">
          <img class="card-img card-img-left" src="../assets/img/elements/12.jpg" alt="Card image" />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><a href="{{ route('karyawan.index') }}">Data Karyawan</a></h5>
            
            <h2>{{ $karyawanCount }}</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md">
    <div class="card mb-3">
      <div class="row g-0">
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><a href="{{ route('jabatans.index') }}">Data Jabatan</a></h5>
            <h2>{{ $jabatanCount }}</h2>
          </div>
        </div>
        <div class="col-md-4">
          <img class="card-img card-img-right" src="../assets/img/elements/17.jpg" alt="Card image" />
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
