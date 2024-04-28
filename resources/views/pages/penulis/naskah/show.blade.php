@extends('layouts.main')

@section('title', 'Naskah')

@section('content')
    <div class="pagetitle">
        <h1>Naskah</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Penulis</a></li>
                <li class="breadcrumb-item"><a href="{{ route('naskah') }}">Naskah</a></li>
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
                        <form class="row g-3 mt-0" action="{{ route('naskah.update', $buku->id_buku) }}" id="stepForm"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold mb-2">Cover<span class="text-danger">*</span>
                                </legend>
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="cover" width="100%">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $buku->judul }}</h5>
                                                <h6 class="card-subtitle mb-3 text-muted">{{ $buku->subjudul }}</h6>
                                                <p class="card-text">
                                                    <a href="{{ asset('storage/' . $buku->file) }}"
                                                        class="btn btn-success d-inline-block mr-2"
                                                        download="{{ $buku->judul }}.docx">Unduh
                                                        File</a>
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
                                    @if ($buku->status == 'Penyerahan')
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
                                    @elseif ($buku->status == 'Diterima')
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
                                    @else
                                        @foreach ($kategori as $kategori)
                                            <div class="col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="{{ $kategori->id_kategori }}"
                                                        name="kategori[{{ $kategori->id_kategori }}]"
                                                        value="{{ $kategori->id_kategori }}">
                                                    <label class="form-check-label" for="{{ $kategori->id_kategori }}">
                                                        {{ $kategori->nama_kategori }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @if ($buku->status == 'Revisi')
                                <div class="col-md-6">
                                    <legend class="col-form-label pt-0 fw-bold mb-1">Seri</legend>
                                    @if ($buku->status == 'Penyerahan')
                                        <select class="form-select" aria-label="Default select example" name="seri">
                                            <option selected value="{{ $buku->seri }}">{{ $buku->seri }}</option>
                                            <option value="" disabled>Pilih Seri (Optional)</option>
                                        </select>
                                    @elseif ($buku->status == 'Diterima')
                                        <select class="form-select" aria-label="Default select example" name="seri">
                                            <option selected value="{{ $buku->seri }}">{{ $buku->seri }}</option>
                                            <option value="" disabled>Pilih Seri (Optional)</option>
                                        </select>
                                    @else
                                        @if ($buku->seri == 'Buku Pelajaran')
                                            <select class="form-select" aria-label="Default select example"
                                                name="seri">
                                                <option value="">Pilih Seri (Optional)</option>
                                                <option selected value="{{ $buku->seri }}">{{ $buku->seri }}
                                                </option>
                                                <option value="Buku Bab">Buku Bab</option>
                                                <option value="Non-Fiksi">Non-Fiksi</option>
                                            </select>
                                        @elseif ($buku->seri == 'Buku Bab')
                                            <select class="form-select" aria-label="Default select example"
                                                name="seri">
                                                <option value="">Pilih Seri (Optional)</option>
                                                <option selected value="{{ $buku->seri }}">{{ $buku->seri }}
                                                </option>
                                                <option value="Buku Bab">Buku Pelajaran</option>
                                                <option value="Non-Fiksi">Non-Fiksi</option>
                                            </select>
                                        @else
                                            <select class="form-select" aria-label="Default select example"
                                                name="seri">
                                                <option value="">Pilih Seri (Optional)</option>
                                                <option selected value="{{ $buku->seri }}">{{ $buku->seri }}
                                                </option>
                                                <option value="Buku Bab">Buku Pelajaran</option>
                                                <option value="Non-Fiksi">Buku Bab</option>
                                            </select>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <legend class="col-form-label pt-0 fw-bold mb-1">Upload File Revisi <span
                                            class="text-danger">*</span>
                                    </legend>
                                    <input class="form-control" type="file" id="formFile" name="file"
                                        accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                        required>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <legend class="col-form-label pt-0 fw-bold mb-1">Seri</legend>
                                    @if ($buku->status == 'Penyerahan')
                                        <select class="form-select" aria-label="Default select example" name="seri">
                                            <option selected value="{{ $buku->seri }}">{{ $buku->seri }}</option>
                                            <option value="" disabled>Pilih Seri (Optional)</option>
                                        </select>
                                    @elseif ($buku->status == 'Diterima')
                                        <select class="form-select" aria-label="Default select example" name="seri">
                                            <option selected value="{{ $buku->seri }}">{{ $buku->seri }}</option>
                                            <option value="" disabled>Pilih Seri (Optional)</option>
                                        </select>
                                    @else
                                        <select class="form-select" aria-label="Default select example" name="seri">
                                            <option selected value="{{ $buku->seri }}" disabled>{{ $buku->seri }}
                                            </option>
                                            <option value="Buku Pelajaran">Buku Pelajaran</option>
                                            <option value="Buku Bab">Buku Bab</option>
                                            <option value="Non-Fiksi">Non-Fiksi</option>
                                        </select>
                                    @endif
                                </div>
                            @endif
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold ">Judul <span class="text-danger">*</span>
                                </legend>
                                @if ($buku->status == 'Penyerahan')
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingJudul"
                                            placeholder="Judul" name="judul" value="{{ $buku->judul }}" required
                                            readonly>
                                        <label for="floatingJudul">Judul</label>
                                    </div>
                                @elseif ($buku->status == 'Diterima')
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingJudul"
                                            placeholder="Judul" name="judul" value="{{ $buku->judul }}" required
                                            readonly>
                                        <label for="floatingJudul">Judul</label>
                                    </div>
                                @else
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingJudul"
                                            placeholder="Judul" name="judul" value="{{ $buku->judul }}" required>
                                        <label for="floatingJudul">Judul</label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold">Subjudul <span class="text-danger">*</span>
                                </legend>
                                @if ($buku->status == 'Penyerahan')
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingSubjudul"
                                            placeholder="Subjudul" name="subjudul" value="{{ $buku->subjudul }}"
                                            required readonly>
                                        <label for="floatingSubjudul">Subjudul</label>
                                    </div>
                                @elseif ($buku->status == 'Diterima')
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingSubjudul"
                                            placeholder="Subjudul" name="subjudul" value="{{ $buku->subjudul }}"
                                            required readonly>
                                        <label for="floatingSubjudul">Subjudul</label>
                                    </div>
                                @else
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingSubjudul"
                                            placeholder="Subjudul" name="subjudul" value="{{ $buku->subjudul }}"
                                            required>
                                        <label for="floatingSubjudul">Subjudul</label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <legend class="col-form-label pt-0 fw-bold">Abstrak <span class="text-danger">*</span>
                                </legend>
                                @if ($buku->status == 'Penyerahan')
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="abstrak"
                                            value="{{ $buku->abstrak }}" style="height: 250px;" required readonly>{{ $buku->abstrak }}</textarea>
                                        <label for="floatingTextarea">Abstrak</label>
                                    </div>
                                @elseif ($buku->status == 'Diterima')
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="abstrak"
                                            value="{{ $buku->abstrak }}" style="height: 250px;" required readonly>{{ $buku->abstrak }}</textarea>
                                        <label for="floatingTextarea">Abstrak</label>
                                    </div>
                                @else
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="abstrak"
                                            value="{{ $buku->abstrak }}" style="height: 250px;" required>{{ $buku->abstrak }}</textarea>
                                        <label for="floatingTextarea">Abstrak</label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <legend class="col-form-label pt-0 fw-bold">List Kontributor <span
                                            class="text-danger">*</span>
                                    </legend>
                                    <input type="hidden" name="kontributor" id="id_kontributor">
                                    <div class="h-10 d-flex align-items-center">
                                        {{-- @if ($buku->status == 'Penyerahan')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#largeModal" disabled>Tambah</button>
                                        @elseif ($buku->status == 'Diterima')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#largeModal" disabled>Tambah</button>
                                        @else
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#largeModal">Tambah</button>
                                        @endif --}}
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
                                                    <input type="hidden"
                                                        name="contributor[{{ $detailContributorBuku->id_users }}]"
                                                        value="{{ $detailContributorBuku->id_users }}">
                                                    <td class="text-center">{{ $detailContributorBuku->name }}</td>
                                                    <td class="text-center">{{ $detailContributorBuku->email }}</td>
                                                    <td class="text-center">{{ $detailContributorBuku->role->nama_role }}
                                                        {{-- </td>
                                                    @if ($buku->status == 'Penyerahan')
                                                        <td class="text-center"><button type="button"
                                                                class="btn btn-danger btn-delete" disabled>Hapus</button>
                                                        </td>
                                                    @elseif ($buku->status == 'Diterima')
                                                        <td class="text-center"><button type="button"
                                                                class="btn btn-danger btn-delete" disabled>Hapus</button>
                                                        </td>
                                                    @else
                                                        <td class="text-center"><button type="button"
                                                                class="btn btn-danger btn-delete">Hapus</button></td>
                                                    @endif --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center">
                                @if ($buku->status == 'Penyerahan')
                                    <a href="{{ route('naskah') }}" class="btn btn-primary submit-step">Kembali</a>
                                @elseif ($buku->status == 'Diterima')
                                    <a href="{{ route('naskah') }}" class="btn btn-primary submit-step">Kembali</a>
                                @else
                                    <button type="submit" class="btn btn-success submit-step" id="simpan">Kirim
                                        Revisi</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    var table = $('#myTableModal').DataTable({
                        serverSide: true,
                        responsive: true,
                        select: true,
                        processing: true,
                        ajax: '{{ route('naskah.datauser') }}',
                        columns: [{
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'role.nama_role',
                                name: 'role.nama_role'
                            },
                            {
                                data: null,
                                render: function(data) {
                                    return '<div class="row justify-content-center">' +
                                        '<div class="col-auto">' +
                                        '<input type="checkbox" class="form-check-input m-1" data-id="' +
                                        data.id_users +
                                        '">' +
                                        '</div>';
                                }
                            }
                        ]
                    });

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
            </script>
    </section>
@endsection
