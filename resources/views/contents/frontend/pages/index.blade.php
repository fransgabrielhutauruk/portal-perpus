@extends('layouts.frontend.main')

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    @include('contents.frontend.partials.main.landing.hero', ['heroData' => $heroData])
    @include('contents.frontend.partials.main.landing.fakta-statistik', ['statisticsData' => $statisticsData])
    @include('contents.frontend.partials.main.landing.akses-koleksi', ['aksesKoleksiData' => $aksesKoleksiData])
    @include('contents.frontend.partials.main.landing.fasilitas', ['fasilitasData' => $fasilitasData])
    @include('contents.frontend.partials.main.landing.layanan', ['layananData' => $layananData])
    @include('contents.frontend.partials.main.landing.panduan', ['panduanData' => $panduanData])
    @include('contents.frontend.partials.main.landing.berita', ['beritaData' => $beritaData])
    @include('contents.frontend.partials.main.landing.faq', ['faqData' => $faqData])
@endsection
