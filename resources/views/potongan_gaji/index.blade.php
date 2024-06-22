@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold">Potongan Gaji</h3>
            <div class="card border-0 shadow mb-3">
                <div class="card-body">
                    <div id="forms">
                        <form id="potonganGajiForm" action="{{ route('potongan-gaji.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="karyawan_id" class="form-label">Nama Karyawan</label>
                                    <select class="form-control select2" id="karyawan_id" name="karyawan_id" required>
                                        <option selected disabled>Pilih Karyawan</option>
                                        @foreach ($karyawan as $k)
                                            <option value="{{ $k->id }}">{{ $k->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="jenis_potongan_gaji_id" class="form-label">Jenis Potongan Gaji</label>
                                    <select class="form-select select2" id="jenis_potongan_gaji_id"
                                        name="jenis_potongan_gaji_id[]" multiple="multiple" required>
                                        @foreach ($jenisPotonganGaji as $jenis)
                                            <option value="{{ $jenis->id }}">{{ $jenis->jenis_potongan }}
                                                ({{ $jenis->jumlah }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select class="form-select" id="bulan" name="bulan" required>
                                        <option selected disabled>Pilih Bulan</option>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <option value="{{ $month }}" {{ date('n') == $month ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <input type="number" class="form-control" id="tahun" name="tahun"
                                        value="{{ date('Y') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                                </div>
                            </div>
                        </form>

                        <form id="editPotonganGajiForm"
                            action="{{ route('potongan-gaji.update', ['potongan_gaji' => ':id']) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3 align-items-center d-flex">
                                <div class="col-md-2 ">
                                    <label for="edit_karyawan_id" class="form-label">Nama Karyawan</label>
                                    <select class="form-control select2" id="edit_karyawan_id" name="karyawan_id" required>
                                        <option selected disabled>Pilih Karyawan</option>
                                        @foreach ($karyawan as $k)
                                            <option value="{{ $k->id }}">{{ $k->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_jenis_potongan_gaji_id" class="form-label">Jenis Potongan Gaji</label>
                                    <select class="form-select select2" id="edit_jenis_potongan_gaji_id"
                                        name="jenis_potongan_gaji_id[]" multiple="multiple" required>
                                        @foreach ($jenisPotonganGaji as $jenis)
                                            <option value="{{ $jenis->id }}">{{ $jenis->jenis_potongan }}
                                                ({{ $jenis->jumlah }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="edit_bulan" class="form-label">Bulan</label>
                                    <select class="form-select" id="edit_bulan" name="bulan" required>
                                        <option selected disabled>Pilih Bulan</option>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <option value="{{ $month }}">
                                                {{ Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="edit_tahun" class="form-label">Tahun</label>
                                    <input type="number" class="form-control" id="edit_tahun" name="tahun" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" id="btn-update">Perbarui</button>
                                    <button type="button" class="btn btn-secondary" id="btn-cancel">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('potongan-gaji.index') }}" method="GET">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label for="filter_bulan" class="form-label">Filter Bulan</label>
                                    <select class="form-select" id="filter_bulan" name="bulan">
                                        <option selected disabled>Pilih Bulan</option>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <option value="{{ $month }}"
                                                {{ ($bulan ?? date('n')) == $month ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }}
                                            </option>
                                        @endfor
                                    </select>

                                </div>
                                <div class="col-md-2">
                                    <label for="filter_tahun" class="form-label">Filter Tahun</label>
                                    <input type="number" class="form-control" id="filter_tahun" name="tahun"
                                        value="{{ $tahun ?? date('Y') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="potonganGajiTable">
                            <thead>
                                <tr>
                                    <th class="text-start">Nama Karyawan</th>
                                    <th class="text-start">Jenis Potongan</th>
                                    <th class="text-start">Total Potongan Gaji</th>
                                    <th class="text-start">Bulan</th>
                                    <th class="text-start">Tahun</th>
                                    <th class="text-start">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($potonganGajiQuery as $item)
                                    <tr>
                                        <td>{{ $item->karyawan->user->name }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($item->jenisPotonganGaji as $jenis)
                                                    <li>{{ $jenis->jenis_potongan }} : Rp.
                                                        {{ number_format($jenis->jumlah, 0, ',', '.') }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-start">Rp.
                                            {{ number_format($item->total_potongan_gaji, 0, ',', '.') }}</td>
                                        <td>{{ $item->bulan }}</td>
                                        <td>{{ $item->tahun }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="#" class="btn rounded-pill btn-warning btn-sm btn-edit"
                                                    data-id="{{ $item->id }}"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('potongan-gaji.destroy', $item->id) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn rounded-pill btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle edit button click
            $('#potonganGajiTable').on('click', '.btn-edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = "{{ route('potongan-gaji.edit', ':id') }}";
                url = url.replace(':id', id);

                $('#potonganGajiForm').hide();
                $('#editPotonganGajiForm').show();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#edit_karyawan_id').val(response.karyawan_id).trigger('change');
                        $('#edit_jenis_potongan_gaji_id').val(response.jenis_potongan_gaji_id)
                            .trigger('change');
                        $('#edit_bulan').val(response.bulan);
                        $('#edit_tahun').val(response.tahun);
                        $('#editPotonganGajiForm').attr('action',
                            "{{ route('potongan-gaji.update', ['potongan_gaji' => ':id']) }}"
                            .replace(':id', id));
                    }
                });
            });

            $('#btn-cancel').click(function(e) {
                e.preventDefault();
                $('#editPotonganGajiForm').hide();
                $('#potonganGajiForm').show();
            });
        });
    </script>
@endpush
