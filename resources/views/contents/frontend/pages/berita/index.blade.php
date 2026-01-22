@extends('layouts.frontend.main')

@breadcrumbs(['Berita', route('frontend.berita.index')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
<x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('perpus-1.jpg', 'perpus')">
    {{ data_get($content, 'header', '') }}
</x-frontend.page-header>

<div class="news-page content-page py-0">
    @include('contents.frontend.partials.main.berita.newest')
</div>
@endsection