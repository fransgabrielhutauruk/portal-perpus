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
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="turnitin"
                    jf-list="datatable">
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject" title="Tolak Request Cek Turnitin">
        <form id="formReject" class="needs-validation" jf-form="reject">
            <input type="hidden" name="reqturnitin_id" value="">
            <x-form.textarea class="mb-2" name="catatan_admin" label="Alasan Penolakan" required />
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="reject" />
        @endslot
    </x-modal>

    <x-modal id="modalApprove" type="centered" :static="true" size="" jf-modal="approve"
        title="Konfirmasi Persetujuan">
        <form id="formApprove" class="needs-validation" jf-form="approve">
            <input type="hidden" name="reqturnitin_id" value="">

            <div class="text-center py-4">
                <h5 class="fw-bold mb-2">Setujui Request Cek Turnitin Ini?</h5>
                <p class="text-muted mb-0">
                    Pastikan dokumen sudah sesuai. <br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
        </form>

        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="approve"></x-btn.form>
        @endslot
    </x-modal>

    <x-modal id="modalDetail" type="centered" :static="true" size="lg" jf-modal="detail" title="Detail Request Cek Turnitin">
        <div class="modal-body p-0">
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
                            <td class="fw-bold w-200px">Nama Dosen</td>
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
                                <i class="ki-outline ki-document fs-3 me-2"></i>Data Dokumen
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold w-200px">Judul Dokumen</td>
                            <td><span id="detail-judul_dokumen">-</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jenis Dokumen</td>
                            <td><span id="detail-jenis_dokumen">-</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Keterangan</td>
                            <td><span id="detail-keterangan">-</span></td>
                        </tr>
                        <tr id="row-file" style="display:none;">
                            <td class="fw-bold">File Dokumen</td>
                            <td><a id="detail-file" href="#" target="_blank" class="btn btn-sm btn-light-primary"><i class="ki-outline ki-file"></i> Lihat File</a></td>
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
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "turnitin",
            base_url: `{{ route('app.req-turnitin.index') }}`
        });

        // Detail modal handler
        $(document).on('click', '[jf-data="turnitin"] [jf-detail]', function() {
            var detailId = $(this).attr('jf-detail');
            
            ajaxRequest({
                link: '{{ route('app.req-turnitin.data') }}/detail',
                data: { reqturnitin_id: detailId },
                callback: function(origin, resp) {
                    var data = resp.data;
                    
                    $('#detail-nama_dosen').text(data.nama_dosen || '-');
                    $('#detail-inisial_dosen').text(data.inisial_dosen || '-');
                    $('#detail-nip').text(data.nip || '-');
                    $('#detail-email_dosen').text(data.email_dosen || '-');
                    $('#detail-prodi').text(data.prodi_nama || data.prodi?.nama_prodi || '-');
                    
                    $('#detail-judul_dokumen').text(data.judul_dokumen || '-');
                    $('#detail-keterangan').text(data.keterangan || '-');
                    
                    // Jenis dokumen badge
                    var jenisBadge = data.jenis_dokumen == 'skripsi' 
                        ? '<span class="badge badge-primary">Skripsi</span>' 
                        : '<span class="badge badge-info">Artikel</span>';
                    $('#detail-jenis_dokumen').html(jenisBadge);
                    
                    // Handle file if exists
                    if (data.file_dokumen) {
                        $('#row-file').show();
                        $('#detail-file').attr('href', data.file_url || '/storage/' + data.file_dokumen);
                    } else {
                        $('#row-file').hide();
                    }
                    
                    // Handle status badge
                    var statusBadge = '';
                    if (data.status == 0) {
                        statusBadge = '<span class="badge badge-warning">Pending</span>';
                        $('#detail-actions-pending').show();
                        $('#detail-actions-pending').data('id', detailId);
                        $('#row-catatan-admin').hide();
                    } else if (data.status == 1) {
                        statusBadge = '<span class="badge badge-success">Disetujui</span>';
                        $('#detail-actions-pending').hide();
                        $('#row-catatan-admin').hide();
                    } else if (data.status == -1) {
                        statusBadge = '<span class="badge badge-danger">Ditolak</span>';
                        $('#detail-actions-pending').hide();
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
    </script>
@endpush
