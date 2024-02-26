@extends('layouts.guest')

@section('title', 'Verify Notice')

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
                            <div class="alert alert-success alert-dismissible text-white" role="alert">
                                <span class="text-sm">Email Verifikasi berhasil dikirim ke email anda. silahkan cek inbox
                                    email anda.</span>
                                {{-- <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
