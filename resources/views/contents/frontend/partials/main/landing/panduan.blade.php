<section class="company-growth fact-statistics-section layanan-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-5">
                    <h3 class="wow fadeInUp">
                        {{ data_get($panduanData, 'content.subtitle') }}
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($panduanData, 'content.title') !!}
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s" style="margin-top: 10px">
                        {!! data_get($panduanData, 'content.description') !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @php
                $allGuides = data_get($panduanData, 'guides', []);
                $displayGuides = array_slice($allGuides, 0, 3);
                $totalGuides = count($allGuides);
            @endphp

            @forelse ($displayGuides as $index => $guide)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="panduan-card wow fadeInUp" data-wow-delay="{{ 0.15 * ($index + 1) }}s">
                        <div class="panduan-card-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <div class="panduan-card-body">
                            <h4 class="panduan-title mb-0">{{ $guide['title'] }}</h4>
                        </div>
                        <div class="panduan-card-footer">
                            <a href="{{ $guide['url'] }}" class="btn-default" target="_blank">
                                <i class="fa-solid fa-eye me-2"></i>Lihat Panduan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada panduan tersedia saat ini.</p>
                    </div>
                </div>
            @endforelse

            @if ($totalGuides > 3)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="panduan-card panduan-card-more wow fadeInUp" data-wow-delay="0.6s">
                        <div class="panduan-card-icon" style="background: var(--primary-main)">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div class="panduan-card-body">
                            <h4 class="panduan-title mb-0">Panduan Lainnya</h4>
                        </div>
                        <div class="panduan-card-footer">
                            <a href="{{ route('frontend.panduan.index') }}" class="btn-default">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    .panduan-section {
        padding: 80px 0;
        background: #fff;
    }

    .panduan-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e8e8e8;
    }

    .panduan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color, #0066cc);
    }

    .panduan-card-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .panduan-card:hover .panduan-card-icon {
        transform: scale(1.1) rotate(-5deg);
    }

    .panduan-card-body {
        flex: 1;
        margin-bottom: 20px;
    }

    .panduan-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #333;
        min-height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .panduan-description {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .panduan-card-footer {
        margin-top: auto;
        width: 100%;
    }

    .panduan-card-footer .btn-default {
        width: 100%;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .panduan-card-footer .btn-default:hover {
        transform: scale(1.05);
    }

    @media (max-width: 991px) {
        .panduan-section {
            padding: 60px 0;
        }

        .panduan-card {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 767px) {
        .panduan-card-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .panduan-title {
            font-size: 1.05rem;
            min-height: auto;
        }

        .panduan-description {
            font-size: 0.85rem;
        }
    }
</style>
