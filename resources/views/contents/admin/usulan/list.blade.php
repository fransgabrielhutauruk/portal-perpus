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
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="usulan" jf-list="datatable">
                    @slot('action')
                    <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="usulan">
                        <i class="bi bi-plus fs-2"></i> Tambah data
                    </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="usulan" title="Pengguna">
        <form id="formData" class="needs-validation" jf-form="usulan">
            <input type="hidden" name="id" value="">
            <x-form.input type="text" class="mb-2" name="name" label="Nama" required />
            <x-form.input type="email" class="mb-2" name="email" label="Email" required />
            <div class="mb-4">
                <x-form.select name="role" label="Role" required>
                    @foreach($pageData->roles as $role)
                    <option value="{{ $role->name }}">
                        {{ $role->name }}
                    </option>
                    @endforeach
                </x-form.select>
            </div>
        </form>
        @slot('action')
        <x-btn.form action="save" class="act-save" jf-save="usulan" />
        @endslot
    </x-modal>

    <x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject" title="Tolak Usulan">
        <form id="formReject" class="needs-validation" jf-form="reject">
            <input type="hidden" name="reqbuku_id" value="">
            <x-form.textarea class="mb-2" name="catatan_admin" label="Alasan Penolakan" required />
        </form>
        @slot('action')
        <x-btn.form action="save" class="act-save" jf-save="reject" />
        @endslot
    </x-modal>
    <x-modal id="modalApprove" 
        type="centered" 
        :static="true" 
        size="" 
        jf-modal="approve" 
        title="Konfirmasi Persetujuan">

        <form id="formApprove" class="needs-validation" jf-form="approve">
            <input type="hidden" name="reqbuku_id" value="">

            <div class="text-center py-4">
                <i class="ki-outline ki-warning text-warning fs-1 mb-3"></i>
                <h5 class="fw-bold mb-2">Setujui Usulan Ini?</h5>
                <p class="text-muted mb-0">
                    Pastikan data usulan sudah benar. Tindakan ini tidak dapat dibatalkan.
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
        jForm.init({
            name: "usulan",
            base_url: `{{ route('app.usulan.index') }}`
        })   
    </script>
    @endpush 