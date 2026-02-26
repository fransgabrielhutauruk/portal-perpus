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
        @include('contents.admin.req.tabs')
        <div class="row">
            <div class="col-md">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="kaperpus"
                    jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="kaperpus">
                            <i class="bi bi-plus fs-2"></i> Tambah Kaperpus
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="kaperpus" title="Data Kaperpus">
        <form id="formData" class="needs-validation" jf-form="kaperpus" enctype="multipart/form-data">
            <input type="hidden" name="kaperpus_id" value="">
            <x-form.input type="text" class="mb-2" name="nama_kaperpus" label="Nama Kepala Perpustakaan" required />
            <div class="mb-2">
                <label class="form-label">Tanda Tangan (Gambar)</label>
                <input type="file" class="form-control" name="ttd_kaperpus" accept="image/*" />
                <div id="preview-ttd" class="mt-2" style="display:none;">
                    <img id="img-ttd-preview" src="" alt="Preview TTD" class="img-fluid border" style="max-height: 120px;" />
                </div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="kaperpus" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "kaperpus",
            base_url: `{{ route('app.req-bebas-pustaka.index') }}`,
            param: 'kaperpus',
            useFormData: true,
            onEdit: function(data) {
                if (data && data.ttd_kaperpus) {
                    $('#preview-ttd').show();
                    $('#img-ttd-preview').attr('src', '/uploads/ttd_kaperpus/' + data.ttd_kaperpus);
                } else {
                    $('#preview-ttd').hide();
                }
            }
        });

        $(document).on('change', 'input[name="ttd_kaperpus"]', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-ttd').show();
                    $('#img-ttd-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        $(document).on('click', '[jf-set-active]', function(e) {
            e.preventDefault();
            var id = $(this).attr('jf-set-active');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Jadikan kaperpus ini sebagai yang aktif?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    ajaxRequest({
                        link: `{{ route('app.req-bebas-pustaka.show', ['param1' => 'kaperpus']) }}/set-active`,
                        data: { kaperpus_id: id },
                        swal_success: true,
                        callback: function() {
                            $('table[jf-data="kaperpus"]').DataTable().ajax.reload(null, false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
