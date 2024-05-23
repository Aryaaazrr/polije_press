@extends('layouts.main')

@section('title', 'Pengguna')

@section('content')
    <div class="pagetitle">
        <h1>Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pengguna') }}">Pengguna</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form tambah pengguna</h5>
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
                        <form class="row g-3 needs-validation"
                            action="{{ route('admin.pengguna.update', $users->id_users) }}" method="POST" novalidate>
                            @csrf
                            @method('put')
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingName" placeholder="Your Name"
                                        name="name" value="{{ $users->name }}" readonly>
                                    <label for="floatingName">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingUsername" placeholder="Your Name"
                                        name="username" value="{{ $users->username }}" readonly>
                                    <label for="floatingUsername">Username</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                                        name="password" value="{{ Hash::make($users->password) }}" disabled>
                                    <label for="floatingPassword">Password</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="floatingSelect" aria-label="State" name="role"
                                        required>
                                        <option selected value="{{ $users->id_role }}">
                                            {{ $users->role->nama_role }}</option>
                                        @foreach ($role as $role)
                                            <option value="{{ $role->id_role }}">{{ $role->nama_role }}</option>
                                        @endforeach
                                    </select>
                                    <label for="floatingSelect">Role</label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning">Perbarui</button>
                                <a href="{{ route('admin.pengguna') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection
