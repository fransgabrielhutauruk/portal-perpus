@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection

@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <div class="row">
            <div class="col-md-8">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="user" jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="user">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "user",
            base_url: `{{ route('app.user.index') }}`
        })
    </script>
@endpush
