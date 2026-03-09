@extends('layouts.frontend.main')

@breadcrumbs(['Tentang Kami', '#'])
@breadcrumbs(['Pustakawan', route('frontend.about.pustakawan')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-7.webp', 'perpus')">
        Pustakawan
    </x-frontend.page-header>

    <section class="pustakawan-content">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-8 text-center">
                    <div class="section-title wow fadeInUp">
                        <h3 class="wow fadeInUp">
                            Tentang Kami
                        </h3>
                        <h2>Tim <b>Pustakawan</b></h2>
                        <p class="mt-3">
                            Tim pustakawan profesional yang siap membantu Anda dalam mencari informasi dan memanfaatkan
                            layanan perpustakaan secara optimal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse ($pustakawanList as $index => $pustakawan)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="pustakawan-card wow fadeInUp" data-wow-delay="{{ 0.1 * ($index + 1) }}s">
                            <div class="pustakawan-photo">
                                @if ($pustakawan->foto)
                                    <img src="{{ asset('uploads/pustakawan/' . $pustakawan->foto) }}"
                                        alt="{{ $pustakawan->nama }}">
                                @else
                                    <div class="pustakawan-placeholder">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="pustakawan-info">
                                <h4 class="pustakawan-name">{{ $pustakawan->nama }}</h4>
                                <div class="pustakawan-email">
                                    <i class="fa-solid fa-envelope"></i>
                                    <a href="mailto:{{ $pustakawan->email }}">{{ $pustakawan->email }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fa-solid fa-users fa-3x"></i>
                            <p>Belum ada data pustakawan tersedia.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
