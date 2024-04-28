@extends('layouts.guest')

@section('title', 'Sign In')

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
                            <form action="" role="form" class="text-start" method="POST">
                                @csrf
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Sign In</button>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('google-login') }}" class="btn bg-gradient-danger w-100 my-2 mb-2">
                                        <i class="fab fa-google me-2"></i> Sign in with Google
                                    </a>
                                </div>
                            </form>
                            <p class="mt-2 text-sm text-center">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-info text-gradient font-weight-bold">Sign
                                    Up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
