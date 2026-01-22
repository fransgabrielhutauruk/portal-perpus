@extends('layouts.frontend.main')

@breadcrumbs(['Tentang Kami', '#'])
@breadcrumbs(['Pustakawan', route('frontend.about.pustakawan')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
<x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-2.jpg', 'perpus')">
    Pustakawan
</x-frontend.page-header>

<section class="pustakawan-content">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-lg-8 text-center">
                <div class="section-title wow fadeInUp">
                    <h2><b>Tim Pustakawan</b> Kami</h2>
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
                            @if($pustakawan->foto)
                                <img src="{{ asset('uploads/pustakawan/' . $pustakawan->foto) }}" alt="{{ $pustakawan->nama }}">
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

.pustakawan-content {
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

.pustakawan-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.pustakawan-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.pustakawan-photo {
    position: relative;
    width: 100%;
    padding-top: 133.33%;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.pustakawan-photo img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.pustakawan-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #adb5bd;
}

.pustakawan-info {
    padding: 20px;
    text-align: center;
}

.pustakawan-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pustakawan-email {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #666;
}

.pustakawan-email i {
    color: var(--primary-color, #0066cc);
    font-size: 1rem;
}

.pustakawan-email a {
    color: #666;
    text-decoration: none;
    word-break: break-all;
}

.pustakawan-email a:hover {
    color: var(--primary-color, #0066cc);
    text-decoration: underline;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state i {
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state p {
    font-size: 1.1rem;
    color: #adb5bd;
    margin: 0;
}

@media (max-width: 991px) {
    .pustakawan-content {
        padding: 60px 0;
    }

    .section-title h2 {
        font-size: 2rem;
    }
}

@media (max-width: 767px) {
    .section-title h2 {
        font-size: 1.75rem;
    }

    .section-title p {
        font-size: 1rem;
    }

    .pustakawan-name {
        font-size: 1.05rem;
        min-height: auto;
    }

    .pustakawan-email {
        font-size: 0.85rem;
    }
}
</style>
@endsection
