@extends('layouts.main')

@section('title', 'Pengguna')

@section('content')
    <div class="pagetitle">
        <h1>Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Admin</a></li>
                <li class="breadcrumb-item active">Pengguna</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Data Pengguna</h5>
                            <div class="h-10 d-flex align-items-center">
                                <a href="{{ route('admin.pengguna.create') }}" class="btn btn-success">Tambah</a>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}'
                });
            </script>
        @endif

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: '{{ route('admin.pengguna.data') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'role.nama_role',
                            name: 'role.nama_role'
                        },
                        {
                            data: null,
                            render: function(data) {
                                var createdAt = new Date(data.created_at);
                                var day = createdAt.getDate().toString().padStart(2,
                                    '0');
                                var month = (createdAt.getMonth() + 1).toString().padStart(2,
                                    '0');
                                var year = createdAt.getFullYear();
                                var hours = createdAt.getHours().toString().padStart(2,
                                    '0');
                                var minutes = createdAt.getMinutes().toString().padStart(2,
                                    '0');
                                var formattedCreatedAt = day + '-' + month + '-' + year + ' ' + hours +
                                    ':' + minutes;
                                return '<div class="row justify-content-center">' +
                                    '<div class="col-auto">' +
                                    '<button type="button" class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#basicModal' +
                                    data.id_users +
                                    '"><i class="bi bi-exclamation-circle"></i></button>' +
                                    '<a href="{{ route('admin.pengguna.edit', '') }}/' + data.id_users +
                                    '" class="btn btn-warning m-1"><i class="bi bi-pencil-square"></i></a>' +
                                    '<a href="{{ route('admin.pengguna.destroy', '') }}/' + data
                                    .id_users +
                                    '" class="btn btn-danger m-1"><i class="bi bi-trash"></i></a>' +
                                    '<div class="modal fade" id="basicModal' + data.id_users +
                                    '" tabindex="-1">' +
                                    '<div class="modal-dialog">' +
                                    '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                    '<h5 class="modal-title">' + data.username + '</h5>' +
                                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                    '</div>' +
                                    '<div class="modal-body text-start">' +
                                    '<div>Nama : ' + data.name + '</div>' +
                                    '<div>Username : ' + data.username + '</div>' +
                                    '<div>Tanggal Akun Dibuat : ' + formattedCreatedAt + '</div>' +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            }
                        }
                    ],
                    rowCallback: function(row, data, index) {
                        var dt = this.api();
                        $(row).attr('data-id', data.id);
                        $('td:eq(0)', row).html(dt.page.info().start + index + 1);
                    }
                });

                $('.datatable-input').on('input', function() {
                    var searchText = $(this).val().toLowerCase();

                    $('.table tr').each(function() {
                        var rowData = $(this).text().toLowerCase();
                        if (rowData.indexOf(searchText) === -1) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                });
            });
        </script>
    </section>
@endsection
