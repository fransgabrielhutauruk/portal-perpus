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
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="bebas-pustaka"
                    jf-list="datatable">
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject"
        title="Tolak Request Bebas Pustaka">
        <form id="formReject" class="needs-validation" jf-form="reject">
            <input type="hidden" name="reqbebaspustaka_id" value="">
            <x-form.textarea class="mb-2" name="catatan_admin" label="Alasan Penolakan" required />
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="reject" />
        @endslot
    </x-modal>

    <x-modal id="modalApprove" type="centered" :static="true" size="" jf-modal="approve"
        title="Konfirmasi Persetujuan">
        <form id="formApprove" class="needs-validation" jf-form="approve">
            <input type="hidden" name="reqbebaspustaka_id" value="">

            <div class="text-center py-4">
                <i class="ki-outline ki-warning text-warning fs-1 mb-3"></i>
                <h5 class="fw-bold mb-2">Setujui Request Ini?</h5>
                <p class="text-muted mb-0">
                    Pastikan data request sudah benar. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
        </form>

        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="approve"></x-btn.form>
        @endslot
    </x-modal>

    <x-modal id="modalReset" type="centered" :static="true" size="" jf-modal="reset" title="Reset Request">
        <form id="formReset" class="needs-validation" jf-form="reset">
            <input type="hidden" name="reqbebaspustaka_id" value="">

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

    <x-modal id="modalDetail" type="centered" :static="true" size="lg" jf-modal="detail"
        title="Detail Request Bebas Pustaka">
        <div class="modal-body p-0">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 gy-4 mb-0">
                    <thead>
                        <tr>
                            <th colspan="2" class="fs-6 text-dark fw-bold py-3 d-flex algin-items-center">
                                <i class="ki-outline ki-user fs-3 me-2"></i>Data Mahasiswa
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold w-200px">Nama Mahasiswa</td>
                            <td><span id="detail-nama_mahasiswa">-</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">NIM</td>
                            <td><span id="detail-nim">-</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email</td>
                            <td><span id="detail-email_mahasiswa">-</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Program Studi</td>
                            <td><span id="detail-prodi">-</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Syarat Terpenuhi</td>
                            <td><span id="detail-syarat_terpenuhi">-</span></td>
                        </tr>
                        <tr id="row-link-kp" style="display:none;">
                            <td class="fw-bold">Link Repository KP</td>
                            <td><a id="detail-link_kp_repository" href="#" target="_blank"
                                    class="btn btn-sm btn-light-info"><i class="ki-outline ki-link"></i> Buka Link KP</a>
                            </td>
                        </tr>
                        <tr id="row-link-pa" style="display:none;">
                            <td class="fw-bold">Link Repository PA</td>
                            <td><a id="detail-link_pa_repository" href="#" target="_blank"
                                    class="btn btn-sm btn-light-info"><i class="ki-outline ki-link"></i> Buka Link PA</a>
                            </td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th colspan="2" class="fs-6 text-dark fw-bold py-3 d-flex algin-items-center">
                                <i class="ki-outline ki-information fs-3 me-2"></i>Informasi Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="row-file" style="display:none;">
                            <td class="fw-bold w-200px">File Hasil</td>
                            <td><a id="detail-file" href="#" target="_blank"
                                    class="btn btn-sm btn-light-primary"><i class="ki-outline ki-file"></i> Lihat File</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold w-200px">Status</td>
                            <td><span id="detail-status">-</span></td>
                        </tr>
                        <tr id="row-catatan-admin" style="display:none;">
                            <td class="fw-bold">Catatan Admin</td>
                            <td><span id="detail-catatan_admin">-</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @slot('action')
            <div id="detail-actions-pending" style="display:none;">
                <button type="button" class="btn btn-sm btn-success me-1" id="btn-detail-approve">
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
        jForm.init({
            name: "bebas-pustaka",
            base_url: `{{ route('app.req-bebas-pustaka.index') }}`
        });

        // Detail modal handler
        $(document).on('click', '[jf-data="bebas-pustaka"] [jf-detail]', function() {
            var detailId = $(this).attr('jf-detail');

            ajaxRequest({
                link: '{{ route('app.req-bebas-pustaka.data') }}/detail',
                data: {
                    reqbebaspustaka_id: detailId
                },
                callback: function(origin, resp) {
                    var data = resp.data;

                    $('#detail-nama_mahasiswa').text(data.nama_mahasiswa || '-');
                    $('#detail-nim').text(data.nim || '-');
                    $('#detail-email_mahasiswa').text(data.email_mahasiswa || '-');
                    $('#detail-prodi').text(data.prodi_nama || data.prodi?.nama_prodi || '-');

                    // Handle repository links
                    if (data.link_kp_repository) {
                        $('#row-link-kp').show();
                        $('#detail-link_kp_repository').attr('href', data.link_kp_repository);
                    } else {
                        $('#row-link-kp').hide();
                    }

                    if (data.link_pa_repository) {
                        $('#row-link-pa').show();
                        $('#detail-link_pa_repository').attr('href', data.link_pa_repository);
                    } else {
                        $('#row-link-pa').hide();
                    }

                    // Syarat terpenuhi badge
                    var syaratBadge = data.is_syarat_terpenuhi ?
                        '<span class="badge badge-success">Terpenuhi</span>' :
                        '<span class="badge badge-warning">Belum Terpenuhi</span>';
                    $('#detail-syarat_terpenuhi').html(syaratBadge);

                    // Handle file if exists
                    if (data.file_hasil_bebas_pustaka) {
                        $('#row-file').show();
                        $('#detail-file').attr('href', data.file_url || '/storage/' + data
                            .file_hasil_bebas_pustaka);
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
                    $('#detail-status').html(statusBadge);

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
                $('#formReset input[name="reqbebaspustaka_id"]').val(detailId);
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
                link: '{{ route('app.req-bebas-pustaka.reset') }}',
                data: data,
                swal_success: true,
                callback: function() {
                    $('[jf-modal="reset"]').modal('hide');
                    $('table[jf-data="bebas-pustaka"]').DataTable().ajax.reload(null, false);
                }
            });
        });

        $(document).on('click', '[jf-download]', function(e) {
            e.preventDefault();

            var reqbebaspustakaId = $(this).attr('jf-download');
            var downloadUrl = '{{ route('app.req-bebas-pustaka.download') }}?reqbebaspustaka_id=' +
                reqbebaspustakaId;

            window.location.href = downloadUrl;
        });
    </script>
@endpush
