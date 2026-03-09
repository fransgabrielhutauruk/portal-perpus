<section class="company-growth fact-statistics-section panduan-landing-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-4 mb-lg-5">
                    <h3 class="wow fadeInUp">
                        {{ data_get($panduanData, 'content.subtitle') }}
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($panduanData, 'content.title') !!}
                    </h2>
                    <p class="wow fadeInUp mt-3" data-wow-delay="0.5s">
                        {!! data_get($panduanData, 'content.description') !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-3 g-lg-4 justify-content-center">
            @php
                $allGuides = data_get($panduanData, 'guides', []);
                $displayGuides = array_slice($allGuides, 0, 3);
                $totalGuides = count($allGuides);
            @endphp

            @forelse ($displayGuides as $index => $guide)
                <div class="col-lg-3 col-md-6 col-6">
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
                <div class="col-lg-3 col-md-6 col-6">
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
