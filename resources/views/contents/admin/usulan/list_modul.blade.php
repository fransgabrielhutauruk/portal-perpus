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
    <x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject"
        title="Tolak Request Modul">
        <form id="formReject" class="needs-validation" jf-form="reject">
            <input type="hidden" name="reqmodul_id" value="">
            <x-form.textarea class="mb-2" name="catatan_admin" label="Alasan Penolakan" required />
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="reject" />
        @endslot
    </x-modal>

    {{-- APPROVE MODAL --}}
    <x-modal id="modalApprove" type="centered" :static="true" size="" jf-modal="approve"
        title="Konfirmasi Persetujuan">
        <form id="formApprove" class="needs-validation" jf-form="approve">
            {{-- The standard script looks for 'reqbuku_id' or 'id' based on your JS, 
             ensure your Controller expects 'reqmodul_id' or whatever name you used. 
             If your JS uses a generic ID field, input name="id" is safest. --}}
            <input type="hidden" name="reqmodul_id" value="">

            <div class="text-center py-4">
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

    <x-modal id="modalReset" type="centered" :static="true" size="" jf-modal="reset"
        title="Reset Request">
        <form id="formReset" class="needs-validation" jf-form="reset">
            <input type="hidden" name="reqmodul_id" value="">

            <div class="alert alert-warning d-flex align-items-center">
                <i class="ki-outline ki-information-5 fs-2x me-3"></i>
                <div>
                    <strong>Perhatian:</strong> Data akan dikembalikan ke status <strong>Menunggu</strong>. 
                    Catatan admin akan dihapus.
                </div>
            </div>
        </form>

        @slot('action')
            <x-btn.form action="save" class="act-save btn-warning" jf-save="reset">Reset Request</x-btn.form>
        @endslot
    </x-modal>

    <x-modal id="modalDetail" type="centered" :static="true" size="xl" jf-modal="detail"
        title="Detail Request Modul">
        <div class="modal-body p-0">
            <div class="row gx-5">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-300 gy-4 mb-0">
                            <thead>
                                <tr>
                                    <th colspan="2" class="fs-6 text-dark fw-bold py-3 d-flex align-items-center">
                                        <i class="ki-outline ki-user fs-3 me-2"></i>Data Dosen
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-150px">Nama Dosen</td>
                                    <td><span id="detail-nama_dosen">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Inisial</td>
                                    <td><span id="detail-inisial_dosen">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">NIP</td>
                                    <td><span id="detail-nip">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email</td>
                                    <td><span id="detail-email_dosen">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Program Studi</td>
                                    <td><span id="detail-prodi">-</span></td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="2" class="fs-6 text-dark fw-bold py-3 d-flex align-items-center">
                                        <i class="ki-outline ki-information fs-3 me-2"></i>Informasi Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-150px">Status</td>
                                    <td><span id="detail-status_req">-</span></td>
                                </tr>
                                <tr id="row-catatan-admin" style="display:none;">
                                    <td class="fw-bold">Catatan Admin</td>
                                    <td><span id="detail-catatan_admin">-</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-300 gy-4 mb-0">
                            <thead>
                                <tr>
                                    <th colspan="2" class="fs-6 text-dark fw-bold py-3 d-flex align-items-center">
                                        <i class="ki-outline ki-book-open fs-3 me-2"></i>Data Modul
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-150px">Judul Modul</td>
                                    <td><span id="detail-judul_modul">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Penulis Modul</td>
                                    <td><span id="detail-penulis_modul">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tahun Modul</td>
                                    <td><span id="detail-tahun_modul">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Mata Kuliah</td>
                                    <td><span id="detail-nama_mata_kuliah">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jenis</td>
                                    <td><span id="detail-praktikum">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jumlah Dibutuhkan</td>
                                    <td><span id="detail-jumlah_dibutuhkan">-</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Deskripsi Kebutuhan</td>
                                    <td><span id="detail-deskripsi_kebutuhan">-</span></td>
                                </tr>
                                <tr id="row-file" style="display:none;">
                                    <td class="fw-bold">File</td>
                                    <td><a id="detail-file" href="#" target="_blank" class="btn btn-sm btn-light-primary"><i
                                                class="ki-outline ki-file"></i> Unduh File</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @slot('action')
            <div id="detail-actions-pending" style="display:none;">
                <button type="button" class="btn btn-sm btn-success" id="btn-detail-approve">
                    <i class="ki-outline ki-check fs-3"></i> Setujui
                </button>
                <button type="button" class="btn btn-sm btn-danger" id="btn-detail-reject">
                    <i class="ki-outline ki-cross fs-3"></i> Tolak
                </button>
            </div>
            <div id="detail-actions-reset" style="display:none;">
                <button type="button" class="btn btn-sm btn-warning" id="btn-detail-reset">
                    <i class="ki-outline ki-arrow-circle-left fs-3"></i> Reset Request
                </button>
            </div>
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

        // Detail modal handler for usulan modul
        $(document).on('click', '[jf-data="modul"] [jf-detail]', function() {
            var detailId = $(this).attr('jf-detail');

            ajaxRequest({
                link: '{{ route('app.usulan-modul.data') }}/detail',
                data: {
                    reqmodul_id: detailId
                },
                callback: function(origin, resp) {
                    var data = resp.data;

                    // Populate detail modal - Modul fields
                    $('#detail-nama_dosen').text(data.nama_dosen || '-');
                    $('#detail-inisial_dosen').text(data.inisial_dosen || '-');
                    $('#detail-nip').text(data.nip || '-');
                    $('#detail-email_dosen').text(data.email_dosen || '-');
                    $('#detail-prodi').text(data.prodi_nama || data.prodi?.nama_prodi || '-');

                    $('#detail-judul_modul').text(data.judul_modul || '-');
                    $('#detail-penulis_modul').text(data.penulis_modul || '-');
                    $('#detail-tahun_modul').text(data.tahun_modul || '-');
                    $('#detail-nama_mata_kuliah').text(data.nama_mata_kuliah || '-');
                    $('#detail-jumlah_dibutuhkan').text(data.jumlah_dibutuhkan || '-');
                    $('#detail-deskripsi_kebutuhan').text(data.deskripsi_kebutuhan || '-');

                    // Handle praktikum badge
                    if (data.praktikum == 1 || data.praktikum === true) {
                        $('#detail-praktikum').html('<span class="badge badge-info">Praktikum</span>');
                    } else {
                        $('#detail-praktikum').html('<span class="badge badge-secondary">Teori</span>');
                    }

                    // Handle file if exists
                    if (data.file) {
                        $('#row-file').show();
                        $('#detail-file').attr('href', data.file_url || '/storage/' + data.file);
                    } else {
                        $('#row-file').hide();
                    }

                    // Handle status badge
                    var statusBadge = '';
                    if (data.status_req == 0) {
                        statusBadge = '<span class="badge badge-warning">Menunggu</span>';
                        $('#detail-actions-pending').show();
                        $('#detail-actions-pending').data('id', detailId);
                        $('#detail-actions-reset').hide();
                        $('#row-catatan-admin').hide();
                    } else if (data.status_req == 1) {
                        statusBadge = '<span class="badge badge-success">Disetujui</span>';
                        $('#detail-actions-pending').hide();
                        $('#detail-actions-reset').show();
                        $('#detail-actions-reset').data('id', detailId);
                        $('#row-catatan-admin').hide();
                    } else if (data.status_req == -1) {
                        statusBadge = '<span class="badge badge-danger">Ditolak</span>';
                        $('#detail-actions-pending').hide();
                        $('#detail-actions-reset').show();
                        $('#detail-actions-reset').data('id', detailId);
                        $('#row-catatan-admin').show();
                        $('#detail-catatan_admin').text(data.catatan_admin || '-');
                    }
                    $('#detail-status_req').html(statusBadge);

                    // Show modal
                    $('[jf-modal="detail"]').modal('show');
                }
            });
        });

        // Handle approve from detail modal
        $(document).on('click', '#btn-detail-approve', function() {
            var detailId = $('#detail-actions-pending').data('id');
            $('[jf-modal="detail"]').modal('hide');

            setTimeout(function() {
                $('[jf-approve="' + detailId + '"]').click();
            }, 300);
        });

        // Handle reject from detail modal
        $(document).on('click', '#btn-detail-reject', function() {
            var detailId = $('#detail-actions-pending').data('id');
            $('[jf-modal="detail"]').modal('hide');

            setTimeout(function() {
                $('[jf-reject="' + detailId + '"]').click();
            }, 300);
        });

        // Handle reset from detail modal
        $(document).on('click', '#btn-detail-reset', function() {
            var detailId = $('#detail-actions-reset').data('id');
            $('[jf-modal="detail"]').modal('hide');

            setTimeout(function() {
                $('#formReset input[name="reqmodul_id"]').val(detailId);
                $('[jf-modal="reset"]').modal('show');
            }, 300);
        });

        // Custom handler for reset save button
        $(document).on('click', '[jf-save="reset"]', function(e) {
            e.preventDefault();
            
            var form = $('#formReset');
            var formData = form.serializeArray();
            var data = {};
            
            formData.forEach(function(field) {
                data[field.name] = field.value;
            });

            ajaxRequest({
                link: '{{ route("app.usulan-modul.reset") }}',
                data: data,
                swal_success: true,
                callback: function() {
                    $('[jf-modal="reset"]').modal('hide');
                    $('table[jf-data="modul"]').DataTable().ajax.reload(null, false);
                }
            });
        });
    </script>
@endpush
