@extends('layouts.frontend.main')

@breadcrumbs(['FAQ', route('frontend.faq.index')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-2.jpg', 'perpus')">
        FAQ
    </x-frontend.page-header>

    <div class="faq-page content-page">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center mb-5">
                        <h2 class="wow fadeInUp">{{ data_get($content, 'page_title') }}</h2>
                        <p class="wow fadeInUp mt-3" data-wow-delay="0.25s">{{ data_get($content, 'page_description') }}</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if (data_get($content, 'total_faq', 0) > 0)
                        @foreach (data_get($content, 'faq_list', []) as $faq)
                            <div class="faq-item wow fadeInUp" data-wow-delay="{{ 0.1 + $loop->index * 0.05 }}s">
                                <div class="faq-question" data-bs-toggle="collapse"
                                    data-bs-target="#faq-{{ $faq->faq_id }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    <div class="faq-question-icon">
                                        <i class="fa-solid fa-circle-question"></i>
                                    </div>
                                    <div class="faq-question-text">
                                        <h5>{{ $faq->pertanyaan }}</h5>
                                    </div>
                                    <div class="faq-toggle-icon">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div id="faq-{{ $faq->faq_id }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                                    data-bs-parent="#faqAccordion">
                                    <div class="faq-answer">
                                        <p>{!! nl2br(e($faq->jawaban)) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada FAQ tersedia saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    html {
        scroll-behavior: auto !important;
    }

    .faq-page {
        padding: 80px 0;
    }

    .faq-item {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        margin-bottom: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        border-color: var(--primary-color, #0066cc);
        box-shadow: 0 5px 20px rgba(0, 102, 204, 0.1);
    }

    .faq-question {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
    }

    .faq-question:hover {
        background: #f8f9fa;
    }

    .faq-question[aria-expanded="true"] {
        background: linear-gradient(135deg, var(--primary-color, #0066cc) 0%, var(--secondary-color, #004999) 100%);
    }

    .faq-question[aria-expanded="true"] .faq-question-icon,
    .faq-question[aria-expanded="true"] .faq-question-text h5,
    .faq-question[aria-expanded="true"] .faq-toggle-icon {
        color: white;
    }

    .faq-question[aria-expanded="true"] .faq-toggle-icon i {
        transform: rotate(180deg);
    }

    .faq-question-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color, #0066cc);
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .faq-question-text {
        flex: 1;
    }

    .faq-question-text h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        line-height: 1.5;
        transition: all 0.3s ease;
    }

    .faq-toggle-icon {
        flex-shrink: 0;
        color: var(--primary-color, #0066cc);
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .faq-toggle-icon i {
        transition: transform 0.3s ease;
    }

    .faq-answer {
        padding: 20px 25px 25px 80px;
        background: #fff;
    }

    .faq-answer p {
        margin: 0;
        color: #555;
        line-height: 1.8;
        font-size: 0.95rem;
    }

    @media (max-width: 991px) {
        .faq-page {
            padding: 60px 0;
        }
    }

    @media (max-width: 767px) {
        .faq-question {
            padding: 15px 20px;
            gap: 12px;
        }

        .faq-question-icon {
            width: 35px;
            height: 35px;
            font-size: 1.3rem;
        }

        .faq-question-text h5 {
            font-size: 1rem;
        }

        .faq-toggle-icon {
            font-size: 1rem;
        }

        .faq-answer {
            padding: 15px 20px 20px 67px;
        }

        .faq-answer p {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .faq-answer {
            padding: 15px 20px 20px 20px;
        }
    }
</style>
