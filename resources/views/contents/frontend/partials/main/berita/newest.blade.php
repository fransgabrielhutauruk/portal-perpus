@php
$newest = data_get($content, 'newest');
@endphp
<section class="newest-section">
    <div class="container">
        <div class="row newest-grid">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                        Berita <span>Terkini</span>
                    </h2>
                </div>
            </div>
            @if ($newest)
            @foreach ($newest as $news)
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ data_get($news, 'url') }}" class="wow fadeInUp newest-grid-item hoverable-card"
                    data-wow-delay="0.1s" data-cursor-text="Lihat">
                    <div class="image-anime newest-grid-image">
                        <img src="{{ data_get($news, 'images.src') }}"
                            alt="{{ data_get($news, 'images.alt') }}">
                    </div>
                    <div class="newest-grid-content">
                        <h3 class="newest-grid-title">
                            {{ data_get($news, 'title') }}
                        </h3>
                        <p class="newest-grid-excerpt">
                            {!! data_get($news, 'excerpt') !!}
                    </p>
                        <span class="newest-grid-date">{{ data_get($news, 'timestamp') }}</span>
                    </div>
                </a>
            </div>
            @endforeach
            @else
            <span class="font-italic">- Deskripsi tentang belum diatur -</span>
            @endif
        </div>
    </div>
</section>