@extends('layouts.main')

@section('title', 'Naskah')

@section('content')
    <div class="pagetitle">
        <h1>Naskah</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Penulis</a></li>
                <li class="breadcrumb-item active">Naskah</li>
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
                            <div class="h-10 d-flex align-items-center">
                                <a href="{{ route('naskah.create') }}" class="btn btn-success">Upload Naskah</a>
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
                                                <th class="text-center">Penulis</th>
                                                <th class="text-center">Judul</th>
                                                <th class="text-center">Subjudul</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Tanggal Terbit</th>
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
                    ajax: '{{ route('naskah.data') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'penulis',
                            name: 'penulis'
                        },
                        {
                            data: 'judul',
                            name: 'judul'
                        },
                        {
                            data: 'subjudul',
                            name: 'subjudul'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'tanggalTerbit',
                            name: 'tanggalTerbit'
                        },
                        {
                            data: null,
                            render: function(data) {
                                var historyRows = '';
                                data.historyRows.forEach(function(historyRow) {
                                    var createdAt = new Date(historyRow.created_at);
                                    var day = createdAt.getDate().toString().padStart(2, '0');
                                    var month = (createdAt.getMonth() + 1).toString().padStart(
                                        2, '0');
                                    var year = createdAt.getFullYear();
                                    var hours = createdAt.getHours().toString().padStart(2,
                                        '0');
                                    var minutes = createdAt.getMinutes().toString().padStart(2,
                                        '0');
                                    var formattedCreatedAt = day + '-' + month + '-' + year +
                                        ' ' + hours +
                                        ':' + minutes;
                                    if (historyRow.file_revisi != null) {
                                        historyRows += '<tr>' +
                                            '<td class="text-center">' + formattedCreatedAt +
                                            '</td>' +
                                            '<td class="text-center">' + (historyRow.users ?
                                                historyRow.users.name : '-') + '</td>' +
                                            '<td class="text-center">' + historyRow.keterangan +
                                            ' - ' +
                                            '<a href="storage/' + historyRow.file_revisi +
                                            '" download="' +
                                            data.judul + '.docx">File Revisi ' + data.judul +
                                            '.docx</a></td>' +
                                            '</tr>';
                                    } else {
                                        historyRows += '<tr>' +
                                            '<td class="text-center">' + formattedCreatedAt +
                                            '</td>' +
                                            '<td class="text-center">' + (historyRow.users ?
                                                historyRow.users.name : '-') + '</td>' +
                                            '<td class="text-center">' + historyRow.keterangan +
                                            '</td>' +
                                            '</tr>';
                                    }
                                });

                                return '<div class="row justify-content-center">' +
                                    '<div class="col-auto">' +
                                    '<button type="button" class="btn btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#basicModal' +
                                    data.id_buku +
                                    '"><i class="bi bi-clipboard-data"></i></button>' +
                                    '<a href="{{ route('naskah.show', '') }}/' + data.id_buku +
                                    '" class="btn btn-info m-1"><i class="bi bi-exclamation-circle"></i></a>' +
                                    '<div class="modal fade" id="basicModal' + data.id_buku +
                                    '" tabindex="-1">' +
                                    '<div class="modal-dialog modal-lg">' +
                                    '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                    '<h5 class="modal-title"> ' + data.judul + '</h5>' +
                                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                    '</div>' +
                                    '<div class="modal-body text-start">' +
                                    '<table class="table table-hover table-bordered" id="myTable">' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th class="text-center">Tanggal</th>' +
                                    '<th class="text-center">Pengguna</th>' +
                                    '<th class="text-center">Keterangan</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody class="text-center">' + historyRows +
                                    '</tbody>' +
                                    '</table>' +
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
