@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold">Karyawan</h3>
            <div class="card shadow border-0">
                <div class="col-md-3">
                    <button class="btn rounded-pill btn-primary mx-3 mt-3" data-bs-toggle="modal"
                        data-bs-target="#modalTambahKaryawan"><i class="bi bi-plus"></i> Tambah Karyawan</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table " id="karyawan-table">
                            <thead>
                                <tr>
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
                        return `
                            <button class="btn rounded-pill btn-warning btn-sm btn-edit" data-id="${data}"><i class="bi bi-pencil"></i></button>
                            <button class="btn rounded-pill btn-danger btn-sm btn-delete" data-id="${data}"><i class="bi bi-trash"></i></button>
                        `;
                    }
                }
            ]
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
                    $('#jabatan_id').val(response.karyawan.jabatan_id); 
                    $('#tanggal_bergabung').val(response.karyawan.tanggal_bergabung);
                    $('#no_hp').val(response.karyawan.no_hp);
                    $('#modalTambahKaryawan').modal('show'); 
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

        // Handle delete karyawan
        $('#karyawan-table').on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            $('#modalDeleteKaryawan').modal('show'); // Tampilkan modal konfirmasi

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
