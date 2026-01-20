@extends('layouts.frontend.main')

@breadcrumbs(['FAQ', route('frontend.faq.index')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
<x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="null">
    FAQ
</x-frontend.page-header>

<div class="faq-page content-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center mb-5">
                    <h2 class="wow fadeInUp">{{ data_get($content, 'page_title') }}</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.25s">{{ data_get($content, 'page_description') }}</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                @if(data_get($content, 'total_faq', 0) > 0)
                    <div class="accordion faq-accordion" id="faqAccordion">
                        @foreach(data_get($content, 'faq_list', []) as $faq)
                            <div class="accordion-item wow fadeInUp" data-wow-delay="{{ 0.1 + ($loop->index * 0.05) }}s">
                                <h2 class="accordion-header" id="heading{{ $faq->faq_id }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $faq->faq_id }}" 
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                            aria-controls="collapse{{ $faq->faq_id }}">
                                        {{ $faq->pertanyaan }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $faq->faq_id }}" 
                                     class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                     aria-labelledby="heading{{ $faq->faq_id }}" 
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <div class="faq-answer">
                                            {!! nl2br(e($faq->jawaban)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="faq-footer text-center mt-5 wow fadeInUp">
                        <div class="alert alert-info d-inline-block">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            <strong>Masih ada pertanyaan?</strong> Silakan hubungi kami untuk informasi lebih lanjut.
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Belum ada FAQ tersedia saat ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .faq-page {
        padding: 80px 0;
    }
    
    .faq-accordion .accordion-item {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 15px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .faq-accordion .accordion-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .faq-accordion .accordion-button {
        padding: 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        background-color: #fff;
        color: #333;
        border: none;
        box-shadow: none;
        transition: all 0.3s ease;
    }
    
    .faq-accordion .accordion-button:not(.collapsed) {
        background-color: rgba(var(--primary-rgb-0), var(--primary-rgb-1), var(--primary-rgb-2), 0.05);
        color: var(--primary-main);
    }
    
    .faq-accordion .accordion-button:focus {
        box-shadow: none;
        outline: none;
    }
    
    .faq-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transition: transform 0.3s ease;
        width: 1.5rem;
        height: 1.5rem;
        flex-shrink: 0;
        margin-left: auto;
    }
    
    .faq-accordion .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23007bff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transform: rotate(-180deg);
    }
    
    .faq-accordion .accordion-body {
        padding: 1.5rem;
        background-color: #fafafa;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .faq-answer {
        color: #555;
        line-height: 1.8;
        font-size: 0.95rem;
    }
    
    .faq-accordion .badge {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .faq-footer .alert {
        border-radius: 10px;
        padding: 1rem 2rem;
        border: none;
        max-width: 500px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .faq-accordion .accordion-button {
            padding: 1rem;
            font-size: 0.9rem;
        }
        
        .faq-accordion .accordion-body {
            padding: 1rem;
        }
        
        .faq-accordion .badge {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Optional: Add smooth scroll behavior for accordion
    document.addEventListener('DOMContentLoaded', function() {
        const accordionButtons = document.querySelectorAll('.accordion-button');
        
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(() => {
                    if (!this.classList.contains('collapsed')) {
                        this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }, 350);
            });
        });
    });
</script>
@endpush
