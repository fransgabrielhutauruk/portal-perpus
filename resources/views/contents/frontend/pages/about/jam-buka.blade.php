@extends('layouts.frontend.main')

@breadcrumbs(['Tentang Kami', '#'])
@breadcrumbs(['Jam Buka Layanan', route('frontend.about.jam-buka')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-depan.webp', 'perpus')">
        Jam Buka Layanan
    </x-frontend.page-header>

    <section class="jam-buka-content">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <div class="section-title wow fadeInUp">
                        <h3 class="wow fadeInUp">
                            Tentang Kami
                        </h3>
                        <h2><b>Jadwal Operasional</b> Perpustakaan</h2>
                        <p class="mt-3">
                            Perpustakaan PCR siap melayani Anda sesuai dengan jadwal operasional berikut.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @foreach ($jadwal as $index => $item)
                        <div class="jadwal-card wow fadeInUp" data-wow-delay="{{ 0.15 * ($index + 1) }}s">
                            <div class="jadwal-icon jadwal-icon-{{ $item['color'] }}">
                                <i class="{{ $item['icon'] }}"></i>
                            </div>
                            <div class="jadwal-content">
                                <h4 class="jadwal-hari">{{ $item['hari'] }}</h4>
                                <p class="jadwal-jam {{ $item['jam'] == 'Libur' ? 'text-danger' : '' }}">
                                    @if ($item['jam'] == 'Libur')
                                        <i class="fa-solid fa-circle-xmark me-2"></i>
                                    @else
                                        <i class="fa-solid fa-clock me-2"></i>
                                    @endif
                                    {{ $item['jam'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="info-box wow fadeInUp" data-wow-delay="0.6s">
                        <div class="info-box-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div class="info-box-content">
                            <h5>Informasi Tambahan</h5>
                            <ul>
                                <li>Perpustakaan tutup pada hari libur nasional dan cuti bersama</li>
                                <li>Jadwal dapat berubah sewaktu-waktu mengikuti kebijakan kampus</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
