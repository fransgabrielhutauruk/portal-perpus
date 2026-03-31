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
            <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="periode" jf-list="datatable">
                @slot('action')
                <div class="d-flex flex-wrap align-items-center gap-3 py-2">
                    <div class="d-flex align-items-center gap-2 text-nowrap">
                        <label class="form-label fs-7 fw-bold mb-0">Jenis Periode:</label>
                        <select id="filter_jenis_periode" class="form-select form-select-sm" style="min-width: 260px;">
                            <option value="all">Semua Jenis</option>
                            @foreach ($pageData->periodeTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="periode">
                        <i class="bi bi-plus fs-2"></i> Tambah data
                    </x-btn>
                </div>
                @endslot
            </x-table.dttable>
        </div>
    </div>
</div>

<x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="periode" title="Periode">
    <form id="formData" class="needs-validation" jf-form="periode">
        <input type="hidden" name="id" value="">
        <x-form.input type="text" class="mb-2" name="nama_periode" label="Nama Periode" required />
        <x-form.select class="mb-2" name="jenis_periode" label="Jenis Periode" required>
            <option value="">Pilih Jenis Periode</option>
            @foreach ($pageData->periodeTypes as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </x-form.select>
        <x-form.input type="date" class="mb-2" name="tanggal_mulai" label="Tanggal Mulai" required />
        <x-form.input type="date" class="mb-2" name="tanggal_selesai" label="Tanggal Selesai" required />
    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="periode" />
    @endslot
</x-modal>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "periode",
        base_url: `{{ route('app.periode.index') }}`
    });

    let periodeTableInstance = null;
    $(document).ready(function() {
        const tableId = '{{ $pageData->dataTable->getTableId() }}';
        periodeTableInstance = $('#' + tableId).DataTable();

        periodeTableInstance.settings()[0].ajax.data = function(d) {
            d.filter_jenis_periode = $('#filter_jenis_periode').val() || 'all';
        };

        $('#filter_jenis_periode').on('change', function() {
            periodeTableInstance.ajax.reload(null, false);
        });
    });
</script>
@endpush