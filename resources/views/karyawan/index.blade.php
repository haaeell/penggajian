@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold">Karyawan</h3>
            <div class="card shadow border-0">
                <div class="col-md-3">
                    @if (Auth::user()->role == 'admin' )
                        <button class="btn rounded-pill btn-primary mx-3 mt-3" data-bs-toggle="modal"
                            data-bs-target="#modalTambahKaryawan"><i class="bi bi-plus"></i> Tambah Karyawan
                        </button>
                       <div class="card m-3">
                           <div class="card-body">
                            <form action="{{ route('import.karyawan') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Pilih file Excel</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary"> <i class="bi bi-upload"></i> Import Karyawan</button>
                            </form>
                           </div>
                       </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="karyawan-table">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>No HP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Karyawan -->
    <div class="modal fade" id="modalTambahKaryawan" tabindex="-1" aria-labelledby="modalTambahKaryawanLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKaryawanLabel">Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahKaryawan">
                    <div class="modal-body">
                        <input type="hidden" id="karyawan_id" name="karyawan_id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik">
                        </div>
                        <div class="mb-3">
                            <label for="jabatan_id" class="form-label">Jabatan</label>
                            <select class="form-select" id="jabatan_id" name="jabatan_id">
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung</label>
                            <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp">
                        </div>
                        <div class="mb-3">
                            <label for="no_rekening" class="form-label">No Rekening</label>
                            <input type="text" class="form-control" id="no_rekening" name="no_rekening">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                        </div>
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="laki-laki">Laki-Laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="simpanBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Karyawan -->
    <div class="modal fade" id="modalDeleteKaryawan" tabindex="-1" aria-labelledby="modalDeleteKaryawanLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteKaryawanLabel">Konfirmasi Hapus Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus karyawan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Show Karyawan -->
    <div class="modal fade" id="modalShowKaryawan" tabindex="-1" aria-labelledby="modalShowKaryawanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalShowKaryawanLabel">Detail Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>NIK:</strong> <span id="showNik"></span></p>
                            <p><strong>Nama:</strong> <span id="showName"></span></p>
                            <p><strong>Email:</strong> <span id="showEmail"></span></p>
                            <p><strong>Jabatan:</strong> <span id="showJabatan"></span></p>
                            <p><strong>Tanggal Bergabung:</strong> <span id="showTanggalBergabung"></span></p>
                            <p><strong>No HP:</strong> <span id="showNoHp"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>No Rekening:</strong> <span id="showNoRekening"></span></p>
                            <p><strong>Alamat:</strong> <span id="showAlamat"></span></p>
                            <p><strong>Tanggal Lahir:</strong> <span id="showTanggalLahir"></span></p>
                            <p><strong>Tempat Lahir:</strong> <span id="showTempatLahir"></span></p>
                            <p><strong>Jenis Kelamin:</strong> <span id="showJenisKelamin"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#karyawan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('getKaryawan') }}',
                columns: [{
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan'
                    },
                    {
                        data: 'tanggal_bergabung',
                        name: 'tanggal_bergabung'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            var actionButtons = `
                                <button class="btn rounded-pill btn-primary btn-sm btn-show" data-id="${data}"><i class="bi bi-eye"></i></button>
                            `;
                            if ({{ Auth::user()->role == 'admin' ? 'true' : 'false' }}) {
                                actionButtons += `
                                    <button class="btn rounded-pill btn-warning btn-sm btn-edit" data-id="${data}" data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan"><i class="bi bi-pencil"></i></button>
                                    <button class="btn rounded-pill btn-danger btn-sm btn-delete" data-id="${data}"><i class="bi bi-trash"></i></button>
                                `;
                            }

                            return actionButtons;
                        }
                    }
                ]
            });

            // Event handler untuk tombol Show
            $('#karyawan-table').on('click', '.btn-show', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/karyawan/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#showNik').text(response.karyawan.nik);
                        $('#showName').text(response.karyawan.user.name);
                        $('#showEmail').text(response.karyawan.user.email);
                        $('#showJabatan').text(response.karyawan.jabatan.jabatan);
                        $('#showTanggalBergabung').text(response.karyawan.tanggal_bergabung);
                        $('#showNoHp').text(response.karyawan.no_hp);
                        $('#showNoRekening').text(response.karyawan.no_rekening);
                        $('#showAlamat').text(response.karyawan.alamat);
                        $('#showTanggalLahir').text(response.karyawan.tanggal_lahir);
                        $('#showTempatLahir').text(response.karyawan.tempat_lahir);
                        $('#showJenisKelamin').text(response.karyawan.jenis_kelamin);
                        $('#modalShowKaryawan').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('Gagal memuat data karyawan.');
                    }
                });
            });

            // Handle form submit for tambah/edit karyawan
            $('#formTambahKaryawan').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var url = '/karyawan';
                var type = 'POST';
                var karyawan_id = $('#karyawan_id').val();

                if (karyawan_id) {
                    url += '/' + karyawan_id;
                    type = 'PUT';
                }

                $.ajax({
                    url: url,
                    type: type,
                    data: formData,
                    success: function(response) {
                        $('#modalTambahKaryawan').modal('hide');
                        $('#karyawan-table').DataTable().ajax.reload();
                        $('#formTambahKaryawan').trigger('reset');
                        toastr.success(response.success);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $('#karyawan-table').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/karyawan/' + id + '/edit',
                    type: 'GET',
                    success: function(response) {
                        $('#karyawan_id').val(response.karyawan.id);
                        $('#name').val(response.karyawan.user.name);
                        $('#email').val(response.karyawan.user.email);
                        $('#nik').val(response.karyawan.nik);
                        $('#jabatan_id').val(response.karyawan.jabatan_id);
                        $('#tanggal_bergabung').val(response.karyawan.tanggal_bergabung);
                        $('#no_hp').val(response.karyawan.no_hp);
                        $('#no_rekening').val(response.karyawan.no_rekening);
                        $('#alamat').val(response.karyawan.alamat);
                        $('#tanggal_lahir').val(response.karyawan.tanggal_lahir);
                        $('#tempat_lahir').val(response.karyawan.tempat_lahir);
                        $('#jenis_kelamin').val(response.karyawan.jenis_kelamin);
                        $('#modalTambahKaryawan').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('Gagal memuat data karyawan untuk diedit.');
                    }
                });
            });

            // Handle delete karyawan
            $('#karyawan-table').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                $('#modalDeleteKaryawan').modal('show');

                // Event listener untuk tombol konfirmasi hapus
                $('#btnConfirmDelete').click(function() {
                    $.ajax({
                        url: '/karyawan/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            $('#modalDeleteKaryawan').modal('hide');
                            $('#karyawan-table').DataTable().ajax.reload();
                            toastr.success(response.success);
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message);
                        }
                    });
                });
            });
        });
    </script>
@endpush
