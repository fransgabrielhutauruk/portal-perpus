@extends('layouts.frontend.main')

@breadcrumbs(['Tentang Kami', '#'])
@breadcrumbs(['Jam Buka Layanan', route('frontend.about.jam-buka')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
<x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-3.jpg', 'perpus')">
    Jam Buka Layanan
</x-frontend.page-header>

<section class="jam-buka-content">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <div class="section-title wow fadeInUp">
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
                                @if($item['jam'] == 'Libur')
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

.jam-buka-content {
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

.jadwal-card {
    background: white;
    border-radius: 15px;
    padding: 25px 30px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 25px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.jadwal-card:hover {
    transform: translateX(10px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.jadwal-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.jadwal-icon-primary {
    background: linear-gradient(135deg, #0066cc 0%, #004999 100%);
}

.jadwal-icon-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.jadwal-icon-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.jadwal-card:hover .jadwal-icon {
    transform: scale(1.1) rotate(5deg);
}

.jadwal-content {
    flex: 1;
}

.jadwal-hari {
    font-size: 1.4rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}

.jadwal-jam {
    font-size: 1.15rem;
    color: #666;
    margin: 0;
    display: flex;
    align-items: center;
}

.jadwal-jam.text-danger {
    color: #dc3545;
    font-weight: 600;
}

.info-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 5px solid var(--primary-color, #0066cc);
    border-radius: 12px;
    padding: 25px 30px;
    margin-top: 30px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.info-box-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-color, #0066cc);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-box-content h5 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 12px;
}

.info-box-content ul {
    margin: 0;
    padding-left: 20px;
}

.info-box-content ul li {
    color: #555;
    line-height: 1.8;
    font-size: 0.95rem;
}

.contact-box {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-top: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.contact-item {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.contact-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, var(--primary-color, #0066cc) 0%, var(--secondary-color, #004999) 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.contact-text h6 {
    font-size: 1rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}

.contact-text p {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
    line-height: 1.6;
}

@media (max-width: 991px) {
    .jam-buka-content {
        padding: 60px 0;
    }

    .section-title h2 {
        font-size: 2rem;
    }

    .jadwal-card {
        padding: 20px;
        gap: 20px;
    }

    .jadwal-icon {
        width: 60px;
        height: 60px;
        font-size: 1.7rem;
    }

    .jadwal-hari {
        font-size: 1.2rem;
    }

    .jadwal-jam {
        font-size: 1rem;
    }

    .info-box {
        flex-direction: column;
        text-align: center;
    }

    .info-box-icon {
        margin: 0 auto;
    }

    .contact-box {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .section-title h2 {
        font-size: 1.75rem;
    }

    .section-title p {
        font-size: 1rem;
    }

    .jadwal-card {
        flex-direction: column;
        text-align: center;
    }

    .jadwal-hari {
        font-size: 1.1rem;
    }

    .jadwal-jam {
        font-size: 0.95rem;
        justify-content: center;
    }

    .contact-item {
        flex-direction: column;
        text-align: center;
    }

    .contact-icon {
        margin: 0 auto;
    }
}
</style>
@endsection
