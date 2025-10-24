@extends('layouts.frontend.main')

@breadcrumbs(['Berita', route('frontend.berita.index')])
@breadcrumbs([data_get($content, 'title', ''), data_get($content, 'url', '')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header', '') }}
    </x-frontend.page-header>

    <div class="berita-page content-page">
        @include('contents.frontend.partials.main.berita.show.content')
    </div>
@endsection