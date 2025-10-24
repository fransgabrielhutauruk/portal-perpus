@php
$latestNews = data_get($content, 'latest_news');
@endphp
<section class="latest-news-section mt-0">
    <h3 class="news-side-title wow fadeInUp" data-wow-delay="0.1s">
        Berita Terbaru
    </h3>

    <div class="latest-news-container">
        @if ($latestNews && count($latestNews) > 0)
        @foreach ($latestNews as $index => $latest)
        <a href="{{ data_get($latest, 'url') }}" class="latest-news-item hoverable-card wow fadeInUp"
            data-wow-delay="0.{{ $index + 1 }}s">
            <figure class="image-anime">
                <img src="{{ data_get($latest, 'images.src') }}" alt="{{ data_get($latest, 'images.alt') }}">
            </figure>

            <div class="latest-news-content py-0">
                <h4>{{ data_get($latest, 'title') }}</h4>
                <span>{{ data_get($latest, 'timestamp') }}</span>
            </div>
        </a>
        @endforeach
        @endif
    </div>

    <div class="latest-news-action">
        <a href="{{ route('frontend.berita.index') }}">
            Lihat Semua Berita
        </a>
    </div>
</section>