@extends('layouts.frontend.main')

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    @include('contents.frontend.partials.main.landing.hero', ['heroData' => $heroData])
    @include('contents.frontend.partials.main.landing.fakta-fasilitas', [
        'statisticsData' => $statisticsData,
        'fasilitasData' => $fasilitasData
    ])
    @include('contents.frontend.partials.main.landing.akses-koleksi', ['aksesKoleksiData' => $aksesKoleksiData])
    @include('contents.frontend.partials.main.landing.layanan', ['layananData' => $layananData])
    @include('contents.frontend.partials.main.landing.berita', ['beritaData' => $beritaData])
    @include('contents.frontend.partials.main.landing.panduan', ['panduanData' => $panduanData])
@endsection
