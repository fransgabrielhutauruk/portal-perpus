@extends('layouts.frontend.main')

@breadcrumbs(['Panduan', route('frontend.panduan.index')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
<x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image', '')">
    {{ data_get($content, 'header', '') }}
</x-frontend.page-header>

<div class="panduan-page content-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-5">
                    <h2 class="wow fadeInUp">{{ data_get($content, 'title') }}</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.25s">{{ data_get($content, 'description') }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse(data_get($content, 'panduan_list', []) as $panduan)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 wow fadeInUp p-3" data-wow-delay="{{ 0.1 + ($loop->index * 0.1) }}s">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: var(--primary-main); border-radius: 10px;">
                                    <i class="fa-solid fa-file-pdf fs-2 text-white"></i>
                                </div>
                                <h5 class="card-title mb-0 flex-grow-1">{{ $panduan->judul }}</h5>
                            </div>
                            
                            @if($panduan->deskripsi)
                                <p class="card-text text-muted mb-3 flex-grow-1">
                                    {{ Str::limit($panduan->deskripsi, 120) }}
                                </p>
                            @endif
                            
                            @if($panduan->file)
                                <a href="{{ route('frontend.panduan.show', ['panduanId' => $panduan->panduan_id]) }}" 
                                   target="_blank" 
                                   class="btn btn-default btn-sm mt-auto">
                                    <i class="fa-solid fa-eye me-2"></i>Lihat Panduan
                                </a>
                            @else
                                <span class="badge bg-secondary mt-auto">File tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Belum ada panduan tersedia saat ini.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .panduan-page {
        padding: 80px 0;
    }
    
    .icon-box {
        background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
    }
</style>
@endpush
