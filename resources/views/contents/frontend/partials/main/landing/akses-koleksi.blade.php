<section class="our-service bg-section akses-koleksi-section">
    <div class="container z-2 position-relative">
        <div class="row">
            <div class="col-lg-5">
                <div class="service-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($aksesKoleksiData, 'subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($aksesKoleksiData, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {!! data_get($aksesKoleksiData, 'description') !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="service-item-list">
                    @forelse (data_get($aksesKoleksiData, 'list', []) as $item)
                        <div class="service-item wow fadeInUp">
                            <div class="service-item-container">
                                <div class="icon-box text-white">
                                    @if(isset($item['logo']) && !empty($item['logo']))
                                        <img src="{{ $item['logo'] }}" alt="{{ $item['name'] }}" style="max-width: 40px; max-height: 40px; object-fit: contain;">
                                    @else
                                        <i class="{{ $item['icon'] }}"></i>
                                    @endif
                                </div>

                                <div class="service-item-content">
                                    <h3>
                                        {{ $item['name'] }}
                                    </h3>
                                    <p>
                                        {{ $item['description'] ?? '' }}
                                    </p>

                                    <div class="service-btn mt-3">
                                        <a href="{{ $item['url'] }}"
                                           class="btn-default btn-highlighted btn-sm"
                                           @if(isset($item['target'])) target="{{ $item['target'] }}" @endif>
                                            Akses {{ $item['name'] }}
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="service-item wow fadeInUp">
                            <div class="service-item-container">
                                <div class="icon-box text-white">
                                    <i class="fa-solid fa-book"></i>
                                </div>

                                <div class="service-item-content">
                                    <h3>
                                        Layanan Tidak Tersedia
                                    </h3>
                                    <p>
                                        Informasi layanan sedang tidak tersedia saat ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
