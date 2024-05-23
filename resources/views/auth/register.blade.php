@extends('layouts.guest')

@section('title', 'Sign Up')

@section('content')
    <div class="page-header align-items-start min-vh-100">
        {{-- <span class="mask bg-gradient-dark opacity-6"></span> --}}
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative  z-index-2">
                            <img src="{{ asset('assets/img/logo-polije-press-long.png') }}"
                                class="h-30 w-30 my-6 top-50 start-50 position-absolute translate-middle" alt="main_logo">
                        </div>
                        <div class="card-body mt-7">
                            {!! session('msg') !!}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible text-white" role="alert">
                                    <span class="text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </span>
                                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <form action="register" role="form" class="text-start" method="POST">
                                @csrf
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ old('username') }}" required>
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm-password"
                                        name="confirm-password" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Sign Up</button>
                                </div>
                            </form>
                            <p class="mt-2 text-sm text-center">
                                Have an account?
                                <a href="{{ route('login') }}" class="text-info text-gradient font-weight-bold">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
