@extends('layouts.main')

@section('title', 'Naskah')

@section('content')
    <div class="pagetitle">
        <h1>Naskah</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Penulis</a></li>
                <li class="breadcrumb-item"><a href="{{ route('naskah') }}">Naskah</a></li>
                <li class="breadcrumb-item active">Upload</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 mt-0" action="{{ route('naskah.store') }}" id="stepForm" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <legend class="col-form-label pt-0 fw-bold mb-1">Seri</legend>

                                <select class="form-select" aria-label="Default select example" name="seri">
                                    <option selected value="">Pilih Seri (Optional)</option>
                                    <option value="Buku Pelajaran">Buku Pelajaran</option>
                                    <option value="Buku Bab">Buku Bab</option>
                                    <option value="Non-Fiksi">Non-Fiksi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold ">Judul <span class="text-danger">*</span>
                                </legend>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingJudul" placeholder="Judul"
                                        name="judul" value="{{ old('judul') }}" required>
                                    <label for="floatingJudul">Judul</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold">Subjudul <span class="text-danger">*</span>
                                </legend>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingSubjudul" placeholder="Subjudul"
                                        name="subjudul" value="{{ old('subjudul') }}" required>
                                    <label for="floatingSubjudul">Subjudul</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <legend class="col-form-label pt-0 fw-bold">Abstrak <span class="text-danger">*</span>
                                </legend>
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="abstrak"
                                        value="{{ old('abstrak') }}" style="height: 250px;" required></textarea>
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
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#largeModal">Tambah</button>
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
                                <table class="table table-hover table-bordered" id="myTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Role</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <tr>
                                            <td>{{ Auth::user()->name }}</td>
                                            <td>Penulis</td>
                                            <td>
                                                <button type="button" class="btn btn-secondary">Anda</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold mb-1">Cover <span class="text-danger">*</span>
                                </legend>
                                <input class="form-control" type="file" id="formCover" name="cover"
                                    accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold mb-1">Upload File <span
                                        class="text-danger">*</span>
                                </legend>
                                <input class="form-control" type="file" id="formFile" name="file"
                                    accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <legend class="col-form-label pt-0 fw-bold mb-2">Kategori <span
                                        class="text-danger">*</span>
                                </legend>
                                <div class="row">
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
                                </div>
                            </div>
                            <div class="form-floating mb-4">
                                <legend class="col-form-label pt-0 fw-bold m-0">Persyaratan <span
                                        class="text-danger">*</span></legend>
                                <p>Anda harus membaca dan mengakui bahwa Anda telah melengkapi
                                    persyaratan di bawah ini
                                    sebelum melanjutkan.
                                </p>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="persyaratan1"
                                            name="persyaratan">
                                        <label class="form-check-label" for="persyaratan1">
                                            Kiriman tersebut belum pernah dipublikasikan sebelumnya, juga belum
                                            pernah
                                            diajukan ke media lain untuk dipertimbangkan (atau penjelasannya telah
                                            diberikan di Komentar kepada Editor).
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="persyaratan2"
                                            name="persyaratan">
                                        <label class="form-check-label" for="persyaratan2">
                                            File penyerahan dalam format file Microsoft Word, RTF, atau
                                            OpenDocument.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="persyaratan3"
                                            name="persyaratan">
                                        <label class="form-check-label" for="persyaratan3">
                                            Jika tersedia, URL untuk referensi telah disediakan.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="persyaratan4"
                                            name="persyaratan">
                                        <label class="form-check-label" for="persyaratan4">
                                            Teksnya diberi spasi tunggal; menggunakan font 12 poin; menggunakan
                                            huruf
                                            miring, bukan garis bawah (kecuali dengan alamat URL); dan semua
                                            ilustrasi,
                                            gambar, dan tabel ditempatkan di dalam teks pada titik yang tepat, bukan
                                            di
                                            bagian akhir.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="persyaratan5"
                                            name="persyaratan">
                                        <label class="form-check-label" for="persyaratan5">
                                            Teks mematuhi persyaratan gaya dan bibliografi yang diuraikan dalam <a
                                                href="https://press.polije.ac.id/publications/index.php/ebooks/about/submissions#authorGuidelines"
                                                target="_blank" rel="noopener noreferrer">Pedoman
                                                Penulis</a> , yang dapat ditemukan di Tentang Pers.
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <legend class="col-form-label pt-0 fw-bold m-0">Kebijakan & Privasi <span
                                        class="text-danger">*</span></legend>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="kebijakanPrivasi"
                                        name="kebijakanPrivasi">
                                    <label class="form-check-label" for="kebijakanPrivasi">
                                        Ya, saya setuju data saya dikumpulkan dan disimpan sesuai dengan <a
                                            href="https://press.polije.ac.id/publications/index.php/ebooks/about/privacy"
                                            target="_blank">pernyataan privasi.</a>
                                    </label>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success submit-step">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oopss...',
                        text: '{{ $errors->first() }}'
                    });
                </script>
            @endif
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
                                var existingRole = $(this).find('td:eq(1)').text();
                                if (existingName === rowData[0] && existingRole === rowData[1]) {
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
                                    role: rowData[1]
                                });
                            }

                            $(this).closest('tr').remove();
                        });

                        selectedData.forEach(function(data) {
                            $('#myTable').append('<tr><td>' + data.name + '</td><td>' + data.role +
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

                    kebijakanPrivasi.forEach(function(checkbox) {
                        if (!checkbox.checked) {
                            iskebijakanPrivasi = false;
                        }
                    });

                    if (!isChecked) {
                        event.preventDefault(); 
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Pilih minimal satu kategori!",
                        });
                        return;
                    }

                    if (!isSyaratChecked) {
                        event.preventDefault(); 
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Anda harus menyetujui semua persyaratan!",
                        });
                        return;
                    }

                    if (!iskebijakanPrivasi) {
                        event.preventDefault(); 
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Anda harus menyetujui kebijakan & privasi!",
                        });
                        return;
                    }
                });
            </script>
    </section>
@endsection
