@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Penulis</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card naskah-penyerahan-card">

                            <div class="card-body">
                                <h5 class="card-title">Naskah Penyerahan</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $jumlahNaskahPenyerahan }}</h6> <span
                                            class="text-muted small pt-2 ps-1">Jumlah Naskah Penyerahan</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card naskah-diterima-card">
                            <div class="card-body">
                                <h5 class="card-title">Naskah Diterima</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $jumlahNaskahDiterima }}</h6><span class="text-muted small pt-2 ps-1">Jumlah
                                            Naskah
                                            Diterima</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card naskah-ditolak-card">

                            <div class="card-body">
                                <h5 class="card-title">Naskah Ditolak</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $jumlahNaskahDitolak }}</h6><span class="text-muted small pt-2 ps-1">Jumlah
                                            Naskah
                                            Ditolak</span>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto h-100">


                            <div class="card-body">
                                <h5 class="card-title">Naskah</h5>

                                <table class="table table-borderless" id="myTable">
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
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div><!-- End Recent Sales -->
                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>

                        <div class="activity">

                            @foreach ($history as $item)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $item->created_at->format('H:i:s') }}</div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        <a href="#" class="fw-bold text-dark">{{ $item->keterangan }}</a>
                                    </div>
                                </div><!-- End activity item-->
                            @endforeach

                        </div>

                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End Right side columns -->

        </div>

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
                                    '<a href="{{ route('admin.naskah.edit', '') }}/' + data.id_buku +
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
                                    '<tbody class="text-center">' + historyRows + '</tbody>' +
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
