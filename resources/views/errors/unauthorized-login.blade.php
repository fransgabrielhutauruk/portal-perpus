@extends('layouts.frontend.main')

@php
    $message = session('error') ?: 'Email tidak diizinkan untuk login.';
@endphp

@section('title', 'Akses Ditolak')

@breadcrumbs(['Akses Ditolak', '#'])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        <span>403</span> - Akses Ditolak
    </x-frontend.page-header>

    <div class="error-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-page-content">
                        <div class="section-title">
                            <h2 class="wow fadeInUp" data-wow-delay="0.25s">Maaf!<span> {{ $message }}</span>
                            </h2>
                        </div>
                        <div class="error-page-content-body">
                            <p class="wow fadeInUp" data-wow-delay="0.5s">
                                Email Anda tidak terdaftar dalam sistem. Hanya akun yang sudah terdaftar yang dapat mengakses halaman admin. Silakan hubungi administrator jika Anda memerlukan akses.
                            </p>
                            <a class="btn-default wow fadeInUp" data-wow-delay="0.75s" href="{{ route('frontend.home') }}">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
