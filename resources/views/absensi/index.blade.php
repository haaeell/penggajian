@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold">Absensi Karyawan</h3>

            @if (session('success') || session('error'))
                <script>
                    $(document).ready(function() {
                        var successMessage = "{{ session('success') }}";
                        var errorMessage = "{{ session('error') }}";

                        if (successMessage) {
                            toastr.success(successMessage);
                        }

                        if (errorMessage) {
                            toastr.error(errorMessage);
                        }
                    });
                </script>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            @if (Auth::user()->role == 'admin')
                                <div class="col-md-12 mb-3">
                                    <form action="{{ route('import.absensi') }}" class="row g-3 align-items-center"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-auto">
                                            <label for="file">Import File Absensi</label>
                                            <input type="file" class="form-control" name="file" required>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary mt-4"
                                                style="background-color: #28a745; border-color: #28a745;"><i
                                                    class="bi bi-upload"></i> Import</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            <form action="{{ route('absensi.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            @php
                                                $months = [
                                                    1 => 'Januari',
                                                    'Februari',
                                                    'Maret',
                                                    'April',
                                                    'Mei',
                                                    'Juni',
                                                    'Juli',
                                                    'Agustus',
                                                    'September',
                                                    'Oktober',
                                                    'November',
                                                    'Desember',
                                                ];
                                            @endphp
                                            <select class="form-select" name="bulan">
                                                @foreach ($months as $monthNumber => $monthName)
                                                    <option value="{{ $monthNumber }}"
                                                        {{ request('bulan', now()->month) == $monthNumber ? 'selected' : '' }}>
                                                        {{ $monthName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <select class="form-select" name="tahun">
                                                @for ($year = 2020; $year <= now()->year; $year++)
                                                    <option value="{{ $year }}"
                                                        {{ request('tahun', now()->year) == $year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="submit" class="btn rounded-pill"
                                            style="background-color: #28a745; color: white; border-color: #28a745;">
                                            <i class="bi bi-search"></i> Tampilkan Absensi
                                        </button>
                                    </div>

                                </div>
                            </form>

                            <form action="{{ route('absensi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ request('bulan', now()->month) }}">
                                <input type="hidden" name="tahun" value="{{ request('tahun', now()->year) }}">

                                @if (auth()->user()->role != 'karyawan')
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table datatable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Jabatan</th>
                                                        <th>Hadir</th>
                                                        <th>Izin</th>
                                                        <th>Sakit</th>
                                                        <th>Alpa</th>

                                                        @if (Auth::user()->role == 'admin')
                                                            <th class="text-center">Aksi</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($karyawan as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="karyawan-name">{{ $item->user->name }}</td>
                                                        <td>{{ $item->jabatan->jabatan }}</td>
                                                        @php
                                                            $isReadOnly = Auth::user()->role == 'pimpinan' ? 'readonly' : '';
                                                        @endphp
                                                        <td>
                                                            <input type="number" style="width: 70px;" class="form-control" name="hadir[{{ $item->id }}]" value="{{ $absensi[$item->id]['hadir'] ?? old('hadir.' . $item->id, 0) }}" {{ $isReadOnly }}>
                                                        </td>
                                                        <td>
                                                            <input type="number" style="width: 70px;" class="form-control" name="izin[{{ $item->id }}]" value="{{ $absensi[$item->id]['izin'] ?? old('izin.' . $item->id, 0) }}" {{ $isReadOnly }}>
                                                        </td>
                                                        <td>
                                                            <input type="number" style="width: 70px;" class="form-control" name="sakit[{{ $item->id }}]}" value="{{ $absensi[$item->id]['sakit'] ?? old('sakit.' . $item->id, 0) }}" {{ $isReadOnly }}>
                                                        </td>
                                                        <td>
                                                            <input type="number" style="width: 70px;" class="form-control" name="alpa[{{ $item->id }}]}" value="{{ $absensi[$item->id]['alpa'] ?? old('alpa.' . $item->id, 0) }}" {{ $isReadOnly }}>
                                                        </td>
                                                        @if (Auth::user()->role == 'admin')
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-danger delete-absensi text-center" data-karyawan-id="{{ $item->id }}" data-karyawan-name="{{ $item->user->name }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                
                                                </tbody>

                                            </table>
                                        </div>
                                        <button type="submit" class="btn rounded-pill btn-primary mt-3"
                                            style="background-color: #28a745; color: white; border-color: #28a745;"><i
                                                class="bi bi-save"></i> Simpan Absensi</button>
                                    </div>
                                </div>
                                @else
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table datatable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Jabatan</th>
                                                        <th>Hadir</th>
                                                        <th>Izin</th>
                                                        <th>Sakit</th>
                                                        <th>Alpa</th>

                                                        @if (Auth::user()->role == 'admin')
                                                            <th class="text-center">Aksi</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($absensi as $absen)
                                                    <tr>
                                                        <td>{{ $absen->bulan }}</td>
                                                        <td>{{ $absen->tahun }}</td>
                                                        <td>{{$absen->karyawan->jabatan->jabatan}}</td>
                                                        <td>{{ $absen->hadir }}</td>
                                                        <td>{{ $absen->izin }}</td>
                                                        <td>{{ $absen->sakit }}</td>
                                                        <td>{{ $absen->alpa }}</td>
                                                    </tr>
                                                @endforeach
                                                
                                                </tbody>

                                            </table>
                                        </div>
                                        <button type="submit" class="btn rounded-pill btn-primary mt-3"
                                            style="background-color: #28a745; color: white; border-color: #28a745;"><i
                                                class="bi bi-save"></i> Simpan Absensi</button>
                                    </div>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteAbsensiModal" tabindex="-1" aria-labelledby="deleteAbsensiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAbsensiModalLabel">Konfirmasi Hapus Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus absensi karyawan <span id="karyawan-name"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var karyawanId;

            $('.delete-absensi').click(function() {
                karyawanId = $(this).data('karyawan-id');
                var karyawanName = $(this).data('karyawan-name');
                $('#karyawan-name').text(karyawanName);
                $('#deleteAbsensiModal').modal('show');
            });

            $('#confirm-delete').click(function() {
                var bulan = $("input[name='bulan']").val();
                var tahun = $("input[name='tahun']").val();

                $.ajax({
                    url: '/absensi/' + karyawanId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(response) {
                        if (response.success) {
                            // Kosongkan nilai input hadir, izin, sakit, dan alpa
                            $('input[name="hadir[' + karyawanId + ']"]').val('');
                            $('input[name="izin[' + karyawanId + ']"]').val('');
                            $('input[name="sakit[' + karyawanId + ']"]').val('');
                            $('input[name="alpa[' + karyawanId + ']"]').val('');
                            $('#deleteAbsensiModal').modal('hide');
                            toastr.success('Absensi karyawan telah dihapus.');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(response) {
                        alert('Terjadi kesalahan, silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endpush
