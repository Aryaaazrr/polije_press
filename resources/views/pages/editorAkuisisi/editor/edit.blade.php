@extends('layouts.main')

@section('title', 'Tugas Editor')

@section('content')
    <div class="pagetitle">
        <h1>Tugas Editor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Editor Akuisisi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('editor.akuisisi.editor') }}">Tugas Editor</a></li>
                <li class="breadcrumb-item active">Pilih Naskah</li>
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
                            <h5 class="card-title">Daftar Naskah</h5>
                        </div>
                        <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <form class="row g-3 " action="{{ route('editor.akuisisi.editor.update', $id) }}"
                                    id="stepForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Penulis</th>
                                                    <th class="text-center">Judul</th>
                                                    <th class="text-center">Subjudul</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
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
        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}'
                });
            </script>
        @endif

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('editor.akuisisi.editor.data', ['id' => ':id']) }}'.replace(':id', window
                            .location
                            .href.split('/').pop()),
                        method: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: 'No.'
                        },
                        {
                            data: 'penulis',
                            name: 'penulis',
                            title: 'Penulis'
                        },
                        {
                            data: 'judul',
                            name: 'judul',
                            title: 'Judul'
                        },
                        {
                            data: 'subjudul',
                            name: 'subjudul',
                            title: 'Subjudul'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            title: 'Status'
                        },
                        {
                            data: null,
                            render: function(data) {
                                if (data.editorType === 'Editor Naskah') {
                                    return '<input type="hidden" name="id_buku" value="' + data
                                        .DT_RowIndex + '">' +
                                        '<button type="submit" class="btn btn-primary assign-editor-btn" data-editor-id="' +
                                        data.id_editor + '" data-book-id="' + data.DT_RowIndex +
                                        '">Kirim Tugas</button>';
                                } else {
                                    return '<input type="hidden" name="id_buku" value="' + data
                                        .DT_RowIndex + '">' +
                                        '<button class="btn btn-success accept-book-btn" data-book-id="' +
                                        data.id_buku + '">Kirim Tugas</button>';
                                }
                            },
                            title: 'Aksi'
                        }
                    ],
                    rowCallback: function(row, data, index) {
                        var dt = this.api();
                        $(row).attr('data-id', data.id);
                        $('td:eq(0)', row).html(dt.page.info().start + index + 1);
                    }
                });

                // $('#myTable').on('click', '.assign-editor-btn', function() {
                //     var editorId = $(this).data('editor-id');
                //     var bookId = $(this).data('book-id');

                //     var requestData = {
                //         _token: '{{ csrf_token() }}',
                //         editorId: editorId,
                //         bookId: bookId,
                //     };

                //     $.ajax({
                //         url: 
                //         method: 'PUT',
                //         data: requestData,
                //         success: function(response) {
                //             console.log('Data berhasil dikirim ke server:', response);
                //         },
                //         error: function(xhr, status, error) {
                //             console.error('Terjadi kesalahan saat mengirim data:', error);
                //         }
                //     });
                // });

                $('#myTable').on('click', '.accept-book-btn', function() {
                    var bookId = $(this).data('book-id');
                    // Tambahkan logika untuk menerima buku oleh editor akuisisi
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
