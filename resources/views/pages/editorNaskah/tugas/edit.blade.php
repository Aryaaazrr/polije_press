@extends('layouts.main')

@section('title', 'Naskah')

@section('content')
    <div class="pagetitle">
        <h1>Tugasku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Editor Naskah</a></li>
                <li class="breadcrumb-item"><a href="{{ route('editor.naskah.tugas') }}">Tugas</a></li>
                <li class="breadcrumb-item active">Lihat Naskah</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! session('msg') !!}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span class="text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                        <form class="row g-3 mt-0" action="{{ route('editor.naskah.update', $buku->id_buku) }}"
                            id="stepForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold mb-2">Cover<span class="text-danger">*</span>
                                </legend>
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="cover" width="250px">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $buku->judul }}</h5>
                                                <h6 class="card-subtitle mb-3 text-muted">{{ $buku->subjudul }}</h6>
                                                <p class="card-text">
                                                    @if ($latestHistory)
                                                        @php
                                                            $revisionNumber = $buku
                                                                ->history()
                                                                ->whereNotNull('file_revisi')
                                                                ->count();
                                                            $fileName =
                                                                $buku->judul . '_Revisi_' . $revisionNumber . '.docx';
                                                        @endphp
                                                        <a href="{{ asset('storage/' . $latestHistory->file_revisi) }}"
                                                            class="btn btn-success d-inline-block mr-2"
                                                            download="{{ $fileName }}">Unduh File Revisi Terbaru</a>
                                                    @else
                                                        <a href="{{ asset('storage/' . $buku->file) }}"
                                                            class="btn btn-success d-inline-block mr-2"
                                                            download="{{ $buku->judul }}.docx">Unduh File</a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary d-inline-block"
                                                        data-bs-toggle="modal" data-bs-target="#komentar">
                                                        Beri Komentar
                                                    </button>
                                                <div class="modal fade" id="komentar" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Beri Komentar</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-floating mb-3">
                                                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"></textarea>
                                                                    <label for="floatingTextarea">Komentar</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold mb-2">Kategori <span class="text-danger">*</span>
                                </legend>
                                <div class="row">
                                    @foreach ($detailKategoriBuku as $detailKategoriBuku)
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" checked
                                                    id="{{ $detailKategoriBuku->id_kategori }}"
                                                    name="kategori[{{ $detailKategoriBuku->id_kategori }}]"
                                                    value="{{ $detailKategoriBuku->id_kategori }}">
                                                <label class="form-check-label"
                                                    for="{{ $detailKategoriBuku->id_kategori }}">
                                                    {{ $detailKategoriBuku->nama_kategori }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach ($kategori as $kategori)
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="{{ $kategori->id_kategori }}"
                                                    name="kategori[{{ $kategori->id_kategori }}]"
                                                    value="{{ $kategori->id_kategori }}" disabled>
                                                <label class="form-check-label" for="{{ $kategori->id_kategori }}">
                                                    {{ $kategori->nama_kategori }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-12">
                                <legend class="col-form-label pt-0 fw-bold mb-1">Seri</legend>

                                <select class="form-select" aria-label="Default select example" name="seri">
                                    <option selected value="{{ $buku->seri }}">{{ $buku->seri }}</option>
                                    <option value="" disabled>Pilih Seri (Optional)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold ">Judul <span class="text-danger">*</span>
                                </legend>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingJudul" placeholder="Judul"
                                        name="judul" value="{{ $buku->judul }}" required readonly>
                                    <label for="floatingJudul">Judul</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold">Subjudul <span class="text-danger">*</span>
                                </legend>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingSubjudul"
                                        placeholder="Subjudul" name="subjudul" value="{{ $buku->subjudul }}" required
                                        readonly>
                                    <label for="floatingSubjudul">Subjudul</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <legend class="col-form-label pt-0 fw-bold">Abstrak <span class="text-danger">*</span>
                                </legend>
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="abstrak"
                                        value="{{ $buku->abstrak }}" style="height: 250px;" required readonly>{{ $buku->abstrak }}</textarea>
                                    <label for="floatingTextarea">Abstrak</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <legend class="col-form-label pt-0 fw-bold">List Kontributor <span
                                            class="text-danger">*</span>
                                    </legend>
                                    <input type="hidden" name="kontributor" id="id_kontributor">
                                    <div class="h-10 d-flex align-items-center">
                                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#largeModal">Tambah</button> --}}
                                    </div>
                                    <div class="modal fade" id="largeModal" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">List Kontributor</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered w-100"
                                                            id="myTableModal">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Name</th>
                                                                    <th class="text-center">Email</th>
                                                                    <th class="text-center">Role</th>
                                                                    <th class="text-center">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-center">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="simpan">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Role</th>
                                                {{-- <th class="text-center">Aksi</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($detailContributorBuku as $detailContributorBuku)
                                                <tr>
                                                    <td class="text-center">{{ $detailContributorBuku->name }}</td>
                                                    <td class="text-center">{{ $detailContributorBuku->email }}</td>
                                                    <td class="text-center">{{ $detailContributorBuku->role->nama_role }}
                                                    </td>
                                                    {{-- <td class="text-center"><button type="button"
                                                            class="btn btn-danger btn-delete">Hapus</button></td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center">
                                @if ($buku->status == 'Penyerahan')
                                    <a href="{{ route('admin.editor') }}" class="btn btn-info submit-step">Tugaskan
                                        Editor</a>
                                @else
                                <input type="submit" value="Penerbitan" name="status" class="btn btn-success"
                                        placeholder="Layak Terbit">
                                    <input type="submit" value="Ditolak" name="status" class="btn btn-danger"
                                        placeholder="Tidak Layak Terbit">
                                    {{-- <input type="submit" value="Diterima" name="status" class="btn btn-success"
                                        placeholder="Diterima">

                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#revisi">Revisi</button>
                                    <div class="modal fade" id="revisi" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Catatan Revisi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating mb-3">
                                                        <textarea class="form-control" name="keterangan" placeholder="Leave a comment here" id="floatingTextarea"
                                                            style="height: 100px;"></textarea>
                                                        <label for="floatingTextarea">Catatan</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" value="Revisi" name="status"
                                                        class="btn btn-primary" placeholder="Kirim Revisi">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    // var table = $('#myTableModal').DataTable({
                    //     serverSide: true,
                    //     responsive: true,
                    //     select: true,
                    //     processing: true,
                    //     ajax: '{{ route('admin.naskah.datauser') }}',
                    //     columns: [{
                    //             data: 'name',
                    //             name: 'name'
                    //         },
                    //         {
                    //             data: 'email',
                    //             name: 'email'
                    //         },
                    //         {
                    //             data: 'role.nama_role',
                    //             name: 'role.nama_role'
                    //         },
                    //         {
                    //             data: null,
                    //             render: function(data) {
                    //                 return '<div class="row justify-content-center">' +
                    //                     '<div class="col-auto">' +
                    //                     '<input type="checkbox" class="form-check-input m-1" data-id="' +
                    //                     data.id_users +
                    //                     '">' +
                    //                     '</div>';
                    //             }
                    //         }
                    //     ]
                    // });

                    $('#simpan').on('click', function() {
                        var selectedData = [];

                        $('#myTableModal input[type="checkbox"].form-check-input:checked').each(function() {
                            var rowData = $(this).closest('tr').find('td').map(function() {
                                return $(this).text();
                            }).get();

                            var exists = false;
                            $('#myTable tbody tr').each(function() {
                                var existingName = $(this).find('td:eq(0)').text();
                                var existingEmail = $(this).find('td:eq(1)').text();
                                var existingRole = $(this).find('td:eq(2)').text();
                                if (existingName === rowData[0] && existingEmail === rowData[1] &&
                                    existingRole === rowData[2]) {
                                    exists = true;
                                    Swal.fire({
                                        icon: "error",
                                        title: "Oops...",
                                        text: "Pengguna telah dipilih!",
                                    });
                                    return false;
                                }
                            });

                            if (!exists) {
                                selectedData.push({
                                    id_users: $(this).data('id'),
                                    name: rowData[0],
                                    email: rowData[1],
                                    role: rowData[2]
                                });
                            }

                            $(this).closest('tr').remove();
                        });

                        selectedData.forEach(function(data) {
                            $('#myTable').append('<tr><td>' + data.name + '</td><td>' + data.email +
                                '</td><td>' + data.role +
                                '</td><td><button type="button" class="btn btn-danger btn-delete">Hapus</button></td></tr>'
                            );
                        });

                        var idKontributorArray = selectedData.map(function(data) {
                            return data.id_users;
                        });
                        $('#id_kontributor').val(JSON.stringify(idKontributorArray));
                    });

                    $(document).on('click', '.btn-delete', function() {
                        $(this).closest('tr').remove();
                        table.ajax.reload();
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

                $('#stepForm').submit(function(event) {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    var syaratCheckboxes = document.querySelectorAll('input[name^="persyaratan"]');
                    // var sebagai = document.querySelectorAll('input[type="radio"]');
                    var kebijakanPrivasi = document.querySelectorAll('input[name^="kebijakanPrivasi"]');
                    var isChecked = false;
                    var isSyaratChecked = true;
                    var iskebijakanPrivasi = true;
                    var isSebagai = false;

                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            isChecked = true;
                        }
                    });

                    syaratCheckboxes.forEach(function(checkbox) {
                        if (!checkbox.checked) {
                            isSyaratChecked = false;
                        }
                    });

                    // sebagai.forEach(function(checkbox) {
                    //     if (checkbox.checked) {
                    //         isSebagai = true;
                    //     }
                    // });

                    kebijakanPrivasi.forEach(function(checkbox) {
                        if (!checkbox.checked) {
                            iskebijakanPrivasi = false;
                        }
                    });

                    if (!isChecked) {
                        event.preventDefault(); // Menghentikan pengiriman formulir
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Pilih minimal satu kategori!",
                        });
                        return;
                    }

                    if (!isSyaratChecked) {
                        event.preventDefault(); // Menghentikan pengiriman formulir
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Anda harus menyetujui semua persyaratan!",
                        });
                        return;
                    }

                    // if (!isSebagai) {
                    //     event.preventDefault(); // Menghentikan pengiriman formulir
                    //     Swal.fire({
                    //         icon: "error",
                    //         title: "Oops...",
                    //         text: "Anda harus memilih salah satu untuk peran pengajuan!",
                    //     });
                    //     return;
                    // }

                    if (!iskebijakanPrivasi) {
                        event.preventDefault(); // Menghentikan pengiriman formulir
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Anda harus menyetujui kebijakan & privasi!",
                        });
                        return;
                    }

                    // Tambahkan validasi lainnya di sini jika diperlukan
                });
            </script>
    </section>
@endsection
