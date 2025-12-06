@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
    @section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
    @endsection

    @section('content')
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <div class="row">
            <div class="col-md">
                {{-- Updated jf-data to 'modul' --}}
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="modul" jf-list="datatable">
                    @slot('action')
                    {{-- Often admins don't add requests manually, but if needed, keep this --}}
                    {{-- <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="modul">
                        <i class="bi bi-plus fs-2"></i> Tambah Request
                    </x-btn> --}}
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
<x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject" title="Tolak Request Modul">
    <form id="formReject" class="needs-validation" jf-form="reject">
        <input type="hidden" name="reqmodul_id" value="">
        <x-form.textarea class="mb-2" name="catatan_admin" label="Alasan Penolakan" required />
    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="reject" />
    @endslot
</x-modal>

    {{-- APPROVE MODAL --}}
<x-modal id="modalApprove" type="centered" :static="true" size="" jf-modal="approve" title="Konfirmasi Persetujuan">
    <form id="formApprove" class="needs-validation" jf-form="approve">
        {{-- The standard script looks for 'reqbuku_id' or 'id' based on your JS, 
             ensure your Controller expects 'reqmodul_id' or whatever name you used. 
             If your JS uses a generic ID field, input name="id" is safest. --}}
        <input type="hidden" name="reqmodul_id" value="">

        <div class="text-center py-4">
            <i class="ki-outline ki-check-circle text-success fs-1 mb-3"></i>
            <h5 class="fw-bold mb-2">Setujui Request Modul Ini?</h5>
            <p class="text-muted mb-0">
                Pastikan data modul dan file yang dilampirkan sudah sesuai. <br>
                Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
    </form>

    @slot('action')
        <x-btn.form action="save" class="act-save" jf-save="approve"></x-btn.form>
    @endslot
</x-modal>
    @endsection

    @push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        // Init Main CRUD
        jForm.init({
            name: "modul", // Matches the jf-data above
            base_url: `{{ route('app.usulan-modul.index') }}` // Ensure this route is correct
        });
    </script>
    @endpush