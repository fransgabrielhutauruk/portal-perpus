<section class="our-service bg-section akses-koleksi-section">
    <div class="container z-2 position-relative">
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title text-center">
                    <h3 class="wow fadeInUp text-white">
                        {{ data_get($aksesKoleksiData, 'subtitle') }}
                    </h3>
                    <h2 class="wow fadeInUp text-white" data-wow-delay="0.25s">
                        {!! data_get($aksesKoleksiData, 'title') !!}
                    </h2>
                    <p class="wow fadeInUp text-white" data-wow-delay="0.5s">
                        {!! data_get($aksesKoleksiData, 'description') !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="service-item-list">
                    <div class="row g-5">
                        @forelse (data_get($aksesKoleksiData, 'list', []) as $item)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="service-item wow fadeInUp h-100"
                                    data-wow-delay="{{ $loop->index * 0.15 + 0.2 }}s">
                                    <div class="service-item-container">
                                        <div class="service-item-content">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-box text-white">
                                                    <i class="{{ $item['icon'] }}"></i>
                                                </div>
                                                <h3 class="mb-0">
                                                    {{ $item['name'] }}
                                                </h3>
                                            </div>
                                            <div class="row justify-content-start">
                                                <div class="col-md-8">
                                                    <p class="mt-0 text-justify">
                                                        {{ $item['description'] ?? '' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="service-btn mt-3">
                                                        <a href="{{ $item['url'] }}"
                                                            class="btn-default btn-highlighted"
                                                            style="padding: 15px 45px 15px 20px;font-size: 14px;"
                                                            @if (isset($item['target'])) target="{{ $item['target'] }}" @endif>
                                                            Akses
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="service-item wow fadeInUp">
                                    <div class="service-item-container">
                                        <div class="service-item-content">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="icon-box text-white">
                                                    <i class="fa-solid fa-book"></i>
                                                </div>
                                                <h3 class="mb-0">
                                                    Layanan Tidak Tersedia
                                                </h3>
                                            </div>
                                            <p>
                                                Informasi layanan sedang tidak tersedia saat ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>