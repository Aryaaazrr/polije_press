@extends('layouts.main')

@section('title', 'Kategori')

@section('content')
    <div class="pagetitle">
        <h1>Kategori</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Admin</a></li>
                <li class="breadcrumb-item active">Kateogri</li>
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
                            <h5 class="card-title">Kategori</h5>
                            <div class="h-10 d-flex align-items-center">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#basicModal">
                                    Tambah Kategori
                                </button>
                                <div class="modal fade" id="basicModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.kategori.store') }}" method="post">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tambah Kategori</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" class="form-control" id="nama_kategori"
                                                        name="nama_kategori" placeholder="Masukkan Nama Kategori">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Kategori</th>
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
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: '{{ route('admin.kategori.data') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'nama_kategori',
                            name: 'nama_kategori'
                        },
                        {
                            data: null,
                            render: function(data) {
                                var baseUrl = window.location.origin;
                                var updateUrl = baseUrl + '/admin/kategori/' + data.id_kategori;
                                return '<div class="row justify-content-center">' +
                                    '<div class="col-auto">' +
                                    '<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#basicModal' +
                                    data.id_kategori + '">' +
                                    '<i class="bi bi-pencil-square"></i>' +
                                    '</button>' +
                                    '<a href="{{ route('admin.kategori.destroy', '') }}/' + data
                                    .id_kategori +
                                    '" class="btn btn-danger m-1"><i class="bi bi-trash"></i></a>' +
                                    '</div>' +
                                    '<div class="modal fade" id="basicModal' + data.id_kategori +
                                    '" tabindex="-1">' +
                                    '<div class="modal-dialog">' +
                                    '<div class="modal-content">' +
                                    '<form id="editForm' + data.id_kategori +
                                    '" action="{{ route('admin.kategori.update', '') }}/' + data
                                    .id_kategori + '" method="POST">' +
                                    '@csrf' +
                                    '@method('PUT')' +
                                    '<div class="modal-header">' +
                                    '<h5 class="modal-title">Edit Kategori</h5>' +
                                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                    '<div class="form-group">' +
                                    '<input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="' +
                                    data.nama_kategori + '">' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="modal-footer">' +
                                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
                                    '<button type="submit" class="btn btn-success">Simpan</button>' +
                                    '</div>' +
                                    '</form>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                // Ajax untuk mengirim form data ke server
                                $('#editForm' + data.id_kategori).submit(function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: "PUT",
                                        url: updateUrl,
                                        data: $(this).serialize(),
                                        success: function(response) {
                                            console.log(response);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error(error);
                                        }
                                    });
                                });
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
