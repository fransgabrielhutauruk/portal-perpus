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

    <x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject"
        title="Tolak Request Cek Turnitin">
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
        <form id="formApprove" class="needs-validation" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="reqturnitin_id" value="">

            <div class="mb-4">
                <label class="form-label fw-bold required">Upload File Hasil Turnitin</label>
                <input type="file" name="file_hasil" id="file_hasil" class="form-control" accept=".pdf,.doc,.docx"
                    required>
                <div class="invalid-feedback">File hasil turnitin wajib diupload</div>
                <div class="form-text">
                    <i class="ki-outline ki-information-5 fs-6 me-1"></i>
                    Format: PDF, DOC, DOCX | Maksimal 10 MB
                </div>
            </div>

            <div class="alert alert-primary d-flex align-items-center">
                <i class="ki-outline ki-information-5 fs-2x me-3"></i>
                <div>
                    <strong>Perhatian:</strong> File hasil turnitin akan dikirim ke email dosen.
                </div>
            </div>
        </form>

        @slot('action')
            <button type="button" class="btn btn-sm btn-primary" id="btn-save-approve">
                <i class="ki-outline ki-check fs-3"></i> Simpan & Setujui
            </button>
        @endslot
    </x-modal>

    <x-modal id="modalReset" type="centered" :static="true" size="" jf-modal="reset"
        title="Reset Request">
        <form id="formReset" class="needs-validation" jf-form="reset">
            <input type="hidden" name="reqturnitin_id" value="">

            <div class="alert alert-warning d-flex align-items-center">
                <i class="ki-outline ki-information-5 fs-2x me-3"></i>
                <div>
                    <strong>Perhatian:</strong> Data akan dikembalikan ke status <strong>Menunggu</strong>. 
                    File hasil turnitin dan catatan admin akan dihapus.
                </div>
            </div>
        </form>

        @slot('action')
            <x-btn.form action="save" class="act-save btn-warning" jf-save="reset">Reset Request</x-btn.form>
        @endslot
    </x-modal>

    <x-modal id="modalDetail" type="centered" :static="true" size="lg" jf-modal="detail"
        title="Detail Request Cek Turnitin">
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
                            <td><a id="detail-file" href="#" target="_blank" class="btn btn-sm btn-light-primary"><i
                                        class="ki-outline ki-file"></i> Lihat File</a></td>
                        </tr>
                        <tr id="row-file-hasil" style="display:none;">
                            <td class="fw-bold">File Hasil Turnitin</td>
                            <td><a id="detail-file-hasil" href="#" target="_blank"
                                    class="btn btn-sm btn-light-success"><i class="ki-outline ki-check-circle"></i> Lihat
                                    Hasil</a></td>
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
            name: "turnitin",
            base_url: `{{ route('app.req-turnitin.index') }}`,
            url: {
                reset: `{{ route('app.req-turnitin.reset') }}`
            }
        });

        // Detail modal handler
        $(document).on('click', '[jf-data="turnitin"] [jf-detail]', function() {
            var detailId = $(this).attr('jf-detail');

            ajaxRequest({
                link: '{{ route('app.req-turnitin.data') }}/detail',
                data: {
                    reqturnitin_id: detailId
                },
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
                    var jenisBadge = data.jenis_dokumen == 'skripsi' ?
                        '<span class="badge badge-primary">Skripsi</span>' :
                        '<span class="badge badge-info">Artikel</span>';
                    $('#detail-jenis_dokumen').html(jenisBadge);

                    // Handle file if exists
                    if (data.file_dokumen) {
                        $('#row-file').show();
                        $('#detail-file').attr('href', data.file_url || '/storage/' + data
                            .file_dokumen);
                    } else {
                        $('#row-file').hide();
                    }

                    // Handle result file if exists
                    if (data.file_hasil_turnitin) {
                        $('#row-file-hasil').show();
                        $('#detail-file-hasil').attr('href', data.file_hasil_url || '/storage/' + data
                            .file_hasil_turnitin);
                    } else {
                        $('#row-file-hasil').hide();
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
                // Set ID dan show modal reset
                $('#formReset input[name="reqturnitin_id"]').val(detailId);
                $('[jf-modal="reset"]').modal('show');
            }, 300);
        });

        // Handle approve button from datatable
        $(document).on('click', '[jf-approve]', function(e) {
            e.preventDefault();
            var reqturnitinId = $(this).attr('jf-approve');

            // Set the reqturnitin_id in the form
            $('#formApprove input[name="reqturnitin_id"]').val(reqturnitinId);

            // Open the approve modal
            $('[jf-modal="approve"]').modal('show');
        });

        // Reset form when approve modal is closed
        $('[jf-modal="approve"]').on('hidden.bs.modal', function() {
            var form = $('#formApprove')[0];
            form.reset();
            form.classList.remove('was-validated');
            $('#formApprove input[name="reqturnitin_id"]').val('');
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
                link: '{{ route("app.req-turnitin.reset") }}',
                data: data,
                swal_success: true,
                callback: function() {
                    $('[jf-modal="reset"]').modal('hide');
                    $('table[jf-data="turnitin"]').DataTable().ajax.reload(null, false);
                }
            });
        });

        // Custom handler for approve form with file upload
        $(document).on('click', '#btn-save-approve', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var form = $('#formApprove')[0];

            // Check if reqturnitin_id is filled
            var reqturnitinId = $('#formApprove input[name="reqturnitin_id"]').val();
            if (!reqturnitinId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'ID Request Turnitin tidak ditemukan. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
                return false;
            }

            // Validate form using Bootstrap validation
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }

            // Check if file is selected
            var fileInput = $('#file_hasil')[0];
            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Silakan pilih file hasil turnitin terlebih dahulu',
                    confirmButtonColor: '#ffc107'
                });
                return false;
            }

            // Check file size (max 10MB)
            var file = fileInput.files[0];
            var maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal 10 MB',
                    confirmButtonColor: '#ffc107'
                });
                return false;
            }

            var formData = new FormData(form);

            // Disable button to prevent double submit
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-2"></i>Menyimpan...');

            // Submit with FormData
            $.ajax({
                url: '{{ route('app.req-turnitin.approve') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Request telah disetujui',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        $('[jf-modal="approve"]').modal('hide');
                        form.reset();
                        form.classList.remove('was-validated');
                        // Reload datatable
                        $('table[jf-data="turnitin"]').DataTable().ajax.reload(null, false);
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg,
                        confirmButtonColor: '#dc3545'
                    });
                },
                complete: function() {
                    // Re-enable button
                    $btn.prop('disabled', false).html(
                        '<i class="ki-outline ki-check fs-3"></i> Simpan & Setujui');
                }
            });
        });
    </script>
@endpush
