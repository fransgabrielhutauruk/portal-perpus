@props([
    'noPadding' => false,
])
@php
    $data = data_get($statisticsData, 'data', []);
@endphp

<section class="company-growth fact-statistics-section bg-section {{ $noPadding ? 'p-0' : '' }}" id="fakta-fasilitas">
    <div class="container">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        Fakta & Fasilitas
                    </h2>
                </div>
            </div>
        </div>

        {{-- Statistics Section --}}
        <div class="row align-items-center mb-5">
            <div class="col-lg-5">
                <div class="company-growth-image">
                    <figure class="image-anime reveal">
                        <img src="{{ data_get($statisticsData, 'image.src') }}"
                            alt="{{ data_get($statisticsData, 'image.alt') }}">
                    </figure>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="section-title ms-2">
                    <h3 class="wow fadeInUp">
                        {{ data_get($statisticsData, 'title') }}
                    </h3>
                    <p class="wow fadeInUp mt-0" data-wow-delay="0.5s">
                        {!! data_get($statisticsData, 'description') !!}
                    </p>
                </div>

                {{-- Statistics Items --}}
                <div class="row fact-statistics-items wow fadeInUp mt-4" data-wow-delay="0.25s">
                    @if (is_array($data) && count($data) > 0)
                        @foreach ($data as $stat)
                            <div class="col-xl-6 col-6">
                                <div class="fact-statistics-item wow fadeInUp fact-statistics-item-sm"
                                    data-wow-delay="{{ $stat['delay'] }}">
                                    <div class="fact-statistics-icon"
                                        style="width: 45px; height: 45px; font-size: 1.2rem;">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </div>
                                    <div class="fact-statistics-content">
                                        <h2 style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                                            @if (isset($stat['counter']) && $stat['counter'])
                                                <span class="counter">{{ $stat['value'] }}</span>
                                            @else
                                                {{ $stat['value'] }}
                                            @endif
                                            {{ $stat['suffix'] ?? '' }}
                                        </h2>
                                        <p style="font-size: 0.85rem; margin-bottom: 0;">{{ $stat['label'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback jika data tidak tersedia --}}
                        <div class="col-12">
                            <div class="text-center">
                                <p>Data statistik sedang tidak tersedia.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Fasilitas Section --}}
        <div class="row align-items-center">
            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                <div class="our-potential-content">
                    <div class="section-title mb-3">
                        <h3 class="wow fadeInUp">
                            {{ data_get($fasilitasData, 'content.title') }}
                        </h3>
                        <p class="wow fadeInUp mt-0" data-wow-delay="0.5s">
                            {!! data_get($fasilitasData, 'content.description') !!}
                        </p>
                    </div>
                    <div class="our-potential-body">
                        <div class="potential-body-list wow fadeInUp" data-wow-delay="0.4s">
                            <ul>
                                @foreach (data_get($fasilitasData, 'highlights', []) as $highlight)
                                    <li>{{ $highlight }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-2">
                <div class="our-potential-img">
                    <figure class="image-anime reveal">
                        <img src="{{ data_get($fasilitasData, 'content.image.src') }}"
                            alt="{{ data_get($fasilitasData, 'content.image.alt') }}" class="object-fit-contain">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>
