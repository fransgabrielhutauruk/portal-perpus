<section class="our-service bg-section bg-section berita-section">
    <div class="container z-2 position-relative">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
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
                    @if (count(data_get($beritaData, 'highlighted', [])) > 0)
                        <div class="swiper" id="berita-swiper">
                            <div class="swiper-wrapper berita-swiper-wrapper">
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
    </div>
</section>

<style>
    .berita-swiper-wrapper {
        height: 550px;
    }

    @media (max-width: 991px) {
        .berita-section {
            padding: 60px 0;
        }

        .berita-section .service-content {
            position: static;
            margin-right: 0;
            text-align: center;
        }

        .berita-section .service-btn {
            justify-content: center;
            display: flex;
        }

        .berita-swiper-wrapper {
            height: 450px;
        }
    }

    @media (max-width: 767px) {
        .berita-section {
            padding: 50px 0;
        }

        .berita-swiper-wrapper {
            height: 300px;
        }

        .berita-section .newest-slider-content h2 {
            font-size: 16px;
            line-height: 1.4;
        }

        .berita-section .newest-slider-content span {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .berita-swiper-wrapper {
            height: 250px;
        }

        .berita-section .newest-slider-content {
            padding: 12px;
        }

        .berita-section .newest-slider-content h2 {
            font-size: 14px;
        }
    }
</style>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const beritaSwiper = new Swiper('#berita-swiper', {
                slidesPerView: 1,
                speed: 1500,
                loop: true,
                spaceBetween: 20,
                autoplay: {
                    delay: 5000,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            })
        })
    </script>
@endpush
