@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="usulan-page content-page">
        {{-- CHANGE THIS LINE: Point to the new partial we just created --}}
        @include('contents.frontend.partials.main.bebas_pustaka.form', ['content' => $content])
    </div>
@endsection