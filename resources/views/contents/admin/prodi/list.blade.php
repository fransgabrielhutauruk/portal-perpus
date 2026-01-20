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
            <div class="col-md">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="prodi" jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="prodi">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="prodi" title="prodi">
        <form id="formData" class="needs-validation" jf-form="prodi">
            <input type="hidden" name="prodi_id" value="">
            <x-form.input type="text" class="mb-2" name="nama_prodi" label="Nama prodi" required />
            <x-form.input type="text" class="mb-2" name="alias_prodi" label="Alias Prodi" required />
            <x-form.input type="text" class="mb-2" name="alias_jurusan" label="Alias Jurusan" required />
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="prodi" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "prodi",
            base_url: `{{ route('app.prodi.index') }}`
        })
    </script>
@endpush
