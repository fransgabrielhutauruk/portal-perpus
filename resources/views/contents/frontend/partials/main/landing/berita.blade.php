<section class="our-service bg-section berita-section">
    <div class="container z-2 position-relative">
        <div class="row">
            <div class="col-lg-4">
                <div class="service-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($beritaData, 'content.subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($beritaData, 'content.title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {{ data_get($beritaData, 'content.description') }}
                        </p>

                        <div class="service-btn mt-3">
                            <a href="{{ route('frontend.berita.index') }}" class="btn-default btn-highlighted btn-sm">
                                Berita Lainnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <section class="newest-section">
                    @if(count(data_get($beritaData, 'highlighted', [])) > 0)
                        <div class="swiper" id="newest-swiper">
                            <div class="swiper-wrapper" style="height: 550px;">
                                @foreach (data_get($beritaData, 'highlighted', []) as $highlight)
                                    <div class="swiper-slide">
                                        <a href="{{ data_get($highlight, 'url') }}" data-cursor-text="Lihat">
                                            <div class="newest-slider-img">
                                                <img src="{{ data_get($highlight, 'images.src') }}"
                                                     alt="{{ data_get($highlight, 'images.alt') }}">
                                            </div>
                                            <div class="newest-slider-content">
                                                <h2>{{ data_get($highlight, 'title') }}</h2>
                                                <span>{{ data_get($highlight, 'timestamp') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-white">Belum ada berita tersedia</p>
                        </div>
                    @endif
                </section>
            </div>
        </div>
        
        @if(data_get($beritaData, 'newest'))
            <div class="row mt-4">
                <div class="col-12">
                    <section class="newest-section">
                        <div class="row newest-grid">
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="section-title mb-0">
                                    <h3 class="wow fadeInUp" style="color:white;">
                                        {{ data_get($beritaData, 'content.sections.newest_title') }}
                                    </h3>
                                </div>
                                <a href="{{ data_get($beritaData, 'newest.url') }}"
                                   class="wow fadeInUp newest-grid-item hoverable-card" data-wow-delay="0.1s"
                                   data-cursor-text="Lihat">
                                    <div class="image-anime newest-grid-image">
                                        <img src="{{ data_get($beritaData, 'newest.images.src') }}"
                                             alt="{{ data_get($beritaData, 'newest.images.alt') }}">
                                    </div>
                                    <div class="newest-grid-content">
                                        <div class="newest-grid-title" style="color:white;">
                                            {{ data_get($beritaData, 'newest.title') }}
                                        </div>
                                        <span class="newest-grid-date"
                                              style="color:white;">{{ data_get($beritaData, 'newest.timestamp') }}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        @endif
    </div>
</section>
