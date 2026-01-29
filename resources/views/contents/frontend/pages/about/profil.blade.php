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
                    <div class="section-title wow fadeInUp">
                        <h3 class="wow fadeInUp">
                            Tentang Kami
                        </h3>
                        <h2>Profil <b>Perpustakaan PCR</b></h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
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

    <style>
        .breadcrumb-item {
            color: rgba(255, 255, 255, 0.8);
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: white;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.6);
        }

        .sejarah-content {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.7;
        }

        .content-text p {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 1.5rem;
        }

        .content-text p.lead {
            font-size: 1.25rem;
            font-weight: 500;
            color: #333;
            line-height: 1.8;
        }

        .highlight-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid var(--primary-color, #0066cc);
            border-radius: 10px;
            padding: 25px 30px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .highlight-icon {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color, #0066cc) 0%, var(--secondary-color, #004999) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }

        .highlight-content h4 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .highlight-content p {
            margin: 0;
            color: #555;
            line-height: 1.7;
        }

        .info-cards {
            margin-top: 40px;
        }

        .info-card {
            background: white;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .info-card:hover {
            border-color: var(--primary-color, #0066cc);
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.15);
            transform: translateY(-5px);
        }

        .info-card-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color, #0066cc) 0%, var(--secondary-color, #004999) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .info-card:hover .info-card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .info-card h5 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 12px;
        }

        .info-card p {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 991px) {
            .page-header {
                padding: 50px 0 30px;
            }

            .page-header-content h1 {
                font-size: 2rem;
            }

            .sejarah-content {
                padding: 60px 0;
            }

            .content-box {
                padding: 30px 20px;
            }

            .highlight-box {
                flex-direction: column;
                text-align: center;
            }

            .highlight-icon {
                margin: 0 auto;
            }
        }

        @media (max-width: 767px) {
            .page-header-content h1 {
                font-size: 1.75rem;
            }

            .content-text p.lead {
                font-size: 1.1rem;
            }

            .content-text p {
                font-size: 1rem;
            }
        }
    </style>
@endsection
