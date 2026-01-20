@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
<x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title"></x-theme.toolbar>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
    data-delay="0">
    <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="faq" jf-list="datatable">
        @slot('action')
        <x-btn type="primary" link="{{ route('app.faq.show', ['param1' => 'form']) }}">
            <i class="bi bi-plus fs-2"></i> Tambah FAQ
        </x-btn>
        @endslot
    </x-table.dttable>
</div>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "faq",
        base_url: `{{ route('app.faq.index') }}`,
    })
</script>
@endpush
