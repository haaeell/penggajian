@extends('layouts.dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold">Users</h3>
            <div class="card shadow border-0">
                <div class="col-md-3">
                    <button class="btn rounded-pill mx-3 mt-3" style="background-color: #28a745; color: white; border-color: #28a745;" id="btn-add">
                        <i class="bi bi-plus"></i> Tambah User
                    </button>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="users-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop through users data --}}
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="{{ $user->roleBadgeClass }}">{{ $user->role }}</span></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $user->id }}"><i
                                                    class="bi bi-pencil"></i> Edit</button>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $user->id }}"><i class="bi bi-trash"></i> Delete</button>
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

    @include('users.modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('getUsers') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    api.rows().every(function() {
                        var rowData = this.data();
                        var roleCell = this.node().getElementsByTagName('td')[2];
                        if (roleCell) {
                            var roleValue = rowData.role;
                            var badgeClass = '';
                            switch (roleValue) {
                                case 'admin':
                                    badgeClass = 'badge rounded-pill bg-danger';
                                    break;
                                case 'pimpinan':
                                    badgeClass = 'badge rounded-pill bg-success';
                                    break;
                                case 'karyawan':
                                    badgeClass = 'badge rounded-pill bg-primary';
                                    break;
                                default:
                                    badgeClass = 'badge bg-secondary';
                                    break;
                            }
                            roleCell.innerHTML = '<span class="' + badgeClass + '">' +
                                roleValue + '</span>';
                        }
                    });
                }
            });

            $('#btn-add').click(function() {
                $('#userForm').trigger("reset");
                $('#userModalLabel').html("Add User");
                $('#btn-save').html("Add");
                $('#karyawan-fields').hide();
                $('#userModal').modal('show');
            });

            $('#role').change(function() {
                if ($(this).val() === 'karyawan') {
                    $('#karyawan-fields').show();
                } else {
                    $('#karyawan-fields').hide();
                }
            });

            $('body').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.get('{{ url('users') }}/' + id + '/edit', function(data) {
                    $('#userModalLabel').html("Edit User");
                    $('#btn-save').html("Update");
                    $('#user_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#role').val(data.role);
                    if (data.role === 'karyawan') {
                        $('#karyawan-fields').show();
                        $('#jabatan_id').val(data.karyawan.jabatan_id);
                        $('#tanggal_bergabung').val(data.karyawan.tanggal_bergabung);
                        $('#no_hp').val(data.karyawan.no_hp);
                    } else {
                        $('#karyawan-fields').hide();
                    }
                    $('#userModal').modal('show');
                });
            });

            $('#userForm').submit(function(e) {
                e.preventDefault();
                var id = $('#user_id').val();
                var url = id ? '{{ url('users') }}/' + id : '{{ url('users') }}';
                var type = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: type,
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#userModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function(response) {
                        toastr.error('Something went wrong!');
                    }
                });
            });

            function handleAjaxError(xhr) {
                var errorMessage = 'Terjadi kesalahan saat menghapus pengguna.';

                switch (xhr.status) {
                    case 403:
                        errorMessage = 'Anda tidak memiliki izin untuk menghapus pengguna ini.';
                        break;
                    case 404:
                        errorMessage = 'Pengguna tidak ditemukan.';
                        break;
                    case 500:
                        errorMessage = 'Terjadi kesalahan internal server.';
                        break;
                    case 422:
                        var errors = xhr.responseJSON.errors;
                        errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        break;
                    default:
                        errorMessage = 'Terjadi kesalahan saat menghubungi server.';
                        break;
                }

                toastr.error(errorMessage);
            }



            $('body').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                $('#confirmDeleteModal').modal('show');

                $('#deleteUserForm').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'users/' + id,
                        type: 'DELETE',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#confirmDeleteModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.success);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            handleAjaxError(xhr);
                        }
                    });
                });
            });


        });
    </script>
@endpush
