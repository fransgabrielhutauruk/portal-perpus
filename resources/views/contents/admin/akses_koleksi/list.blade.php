@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title"></x-theme.toolbar>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="akses-koleksi" jf-list="datatable">
            @slot('action')
                <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="akses-koleksi">
                    <i class="bi bi-plus fs-2"></i> Tambah Akses dan Koleksi
                </x-btn>
            @endslot
        </x-table.dttable>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="akses-koleksi"
        title="Akses dan Koleksi">
        <form id="formData" class="needs-validation" jf-form="akses-koleksi">
            <input type="hidden" name="id" value="">
            <x-form.input type="text" class="mb-2" name="nama_akses_koleksi" label="Nama Akses/Koleksi" required />
            <x-form.textarea class="mb-2" rows="4" label="Deskripsi" name="deskripsi"></x-form.textarea>
            <x-form.input type="url" class="mb-2" name="url" label="URL" required />
            <x-form.input type="number" class="mb-4" name="urutan" label="Urutan" min="0" required />
            <x-form.input type="hidden" name="is_active" value="0"></x-form.input>
            <x-form.switch class="mb-2" label="Aktif" name="is_active" value="1" />
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="akses-koleksi" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "akses-koleksi",
            base_url: `{{ route('app.akses-koleksi.index') }}`,
        })
    </script>
@endpush
