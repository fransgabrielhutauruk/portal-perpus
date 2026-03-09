@extends('layouts.frontend.main')

@breadcrumbs(['Tentang Kami', '#'])
@breadcrumbs(['Profil Perpustakaan', route('frontend.about.profil')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-3.webp', 'perpus')">
        Profil Perpustakaan
    </x-frontend.page-header>

    <section class="sejarah-content">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-8 text-center">
                    <div class="section-title">
                        <h3 class="wow fadeInUp" data-wow-delay="0.4s">
                            Tentang Kami
                        </h3>
                        <h2 class="wow fadeInUp">Profil <b>Perpustakaan PCR</b></h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center wow fadeInLeft" data-wow-delay="0.5s">
                <div class="col-lg-10">
                    <div class="content-text">
                        <p class="lead">
                            Perpustakaan merupakan bagian integral dari sistem pendidikan di Politeknik Caltex Riau
                            (PCR),
                            yang berperan penting dalam menunjang proses pembelajaran, pengajaran, penelitian, serta
                            pengabdian
                            kepada masyarakat.
                        </p>

                        <p>
                            Keberadaan perpustakaan tidak hanya menyediakan sumber informasi yang terpercaya, tetapi
                            juga
                            menciptakan lingkungan belajar yang kondusif bagi sivitas akademika. Mahasiswa, dosen, dan
                            tenaga kependidikan diharapkan dapat memanfaatkan layanan perpustakaan
                            secara optimal untuk mendukung aktivitas akademik dan pengembangan diri.
                        </p>

                        <div class="highlight-box mt-4 mb-4">
                            <div class="highlight-icon">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div class="highlight-content">
                                <h4>Struktur Organisasi</h4>
                                <p>
                                    Perpustakaan Politeknik Caltex Riau (PCR) merupakan bagian yang berada di bawah
                                    koordinasi
                                    Bidang Inovasi, Pengembangan Pembelajaran, dan Perpustakaan. Perpustakaan ini
                                    bertujuan untuk
                                    menyediakan sumber informasi yang relevan, mutakhir, dan terpercaya guna menunjang
                                    kegiatan
                                    akademik di lingkungan kampus.
                                </p>
                            </div>
                        </div>

                        <p>
                            Pengelolaan perpustakaan dibantu oleh staf pustakawan serta tenaga teknis lainnya yang
                            bertanggung
                            jawab atas layanan sirkulasi, layanan referensi, pengembangan koleksi, serta layanan
                            literasi
                            informasi dan teknologi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
