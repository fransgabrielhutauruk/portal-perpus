<section class="company-growth fact-statistics-section layanan-section">
    <div class="container z-2 position-relative">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-4 mb-lg-5">
                    <h3 class="wow fadeInUp">
                        {{ data_get($layananData, 'content.subtitle') }}
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($layananData, 'content.title') !!}
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s">
                        {!! data_get($layananData, 'content.description') !!}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="row g-3 g-lg-4">
            @forelse (data_get($layananData, 'services', []) as $index => $service)
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="layanan-card wow fadeInUp" data-wow-delay="{{ 0.2 * ($index + 1) }}s">
                        <div class="layanan-card-icon" style="color: var(--primary-main)">
                            <i class="{{ $service['icon'] ?? 'fa-solid fa-circle-info' }} fa-2x fa-lg-3x"></i>
                        </div>
                        <div class="layanan-card-body">
                            <h4 class="layanan-card-title">{{ $service['title'] }}</h4>
                            <p class="layanan-card-description d-none d-md-block">{{ $service['description'] }}</p>
                        </div>
                        <div class="layanan-card-footer">
                            <a href="{{ $service['url'] }}" class="btn-default w-100">
                                Akses Layanan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <p>Informasi layanan sedang tidak tersedia saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
