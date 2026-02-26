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
            @foreach (data_get($layananData, 'services', []) as $index => $service)
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
            @endforeach
        </div>
    </div>
</section>

<style>
.layanan-section {
    padding: 80px 0;
}

.layanan-card {
    background: white;
    border-radius: 15px;
    padding: 30px 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
}

.layanan-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.layanan-card-icon {
    text-align: center;
    margin-bottom: 20px;
}

.layanan-card-body {
    flex: 1;
    text-align: center;
    margin-bottom: 20px;
}

.layanan-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
}

.layanan-card-description {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.6;
    margin-bottom: 0;
}

.layanan-card-footer {
    margin-top: auto;
}

.layanan-card-footer .btn {
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.layanan-card-footer .btn:hover {
    transform: scale(1.05);
}

@media (max-width: 991px) {
    .layanan-section {
        padding: 60px 0;
    }
    
    .layanan-card {
        margin-bottom: 0;
    }

    .layanan-card-title {
        font-size: 1.1rem;
    }
    
    .layanan-card-description {
        font-size: 0.9rem;
    }
}

@media (max-width: 767px) {
    .layanan-section {
        padding: 50px 0;
    }

    .layanan-card {
        padding: 20px 14px;
        border-radius: 12px;
    }

    .layanan-card:hover {
        transform: translateY(-5px);
    }

    .layanan-card-icon {
        margin-bottom: 12px;
    }

    .layanan-card-icon i {
        font-size: 1.5rem !important;
    }

    .layanan-card-title {
        font-size: 0.9rem;
        margin-bottom: 8px;
        min-height: auto;
    }
    
    .layanan-card-body {
        margin-bottom: 12px;
    }

    .layanan-card-footer .btn-default {
        padding: 10px 12px 10px 12px;
        font-size: 0.75rem;
    }
}

@media (max-width: 375px) {
    .layanan-card {
        padding: 16px 10px;
    }

    .layanan-card-title {
        font-size: 0.8rem;
    }

    .layanan-card-footer .btn-default {
        padding: 8px 10px;
        font-size: 0.7rem;
    }
}
</style>
