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
                    <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="usulan"
                        jf-list="datatable">
                    </x-table.dttable>
                </div>
            </div>
        </div>

        <x-modal id="modalReject" type="centered" :static="true" size="" jf-modal="reject" title="Tolak Usulan">
            <form id="formReject" class="needs-validation" jf-form="reject">
                <input type="hidden" name="reqbuku_id" value="">
                <x-form.textarea class="mb-2" name="catatan_admin" label="Alasan Penolakan" required />
            </form>
            @slot('action')
                <x-btn.form action="save" class="act-save" jf-save="reject" />
            @endslot
        </x-modal>
        <x-modal id="modalApprove" type="centered" :static="true" size="" jf-modal="approve"
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

        <x-modal id="modalReset" type="centered" :static="true" size="" jf-modal="reset"
            title="Reset Request">
            <form id="formReset" class="needs-validation" jf-form="reset">
                <input type="hidden" name="reqbuku_id" value="">

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
            title="Detail Usulan Buku">
            <div class="modal-body p-0">
                <div class="row gx-5">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-300 gy-4 mb-0">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="fs-6 text-dark fw-bold py-3 d-flex align-items-center">
                                            <i class="ki-outline ki-user fs-3 me-2"></i>Data Pengusul
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold w-150px">Nama Pengusul</td>
                                        <td><span id="detail-nama_req">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email</td>
                                        <td><span id="detail-email_req">-</span></td>
                                    </tr>
                                    <tr id="row-nim" style="display:none;">
                                        <td class="fw-bold">NIM</td>
                                        <td><span id="detail-nim">-</span></td>
                                    </tr>
                                    <tr id="row-nip" style="display:none;">
                                        <td class="fw-bold">NIP</td>
                                        <td><span id="detail-nip">-</span></td>
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
                                            <i class="ki-outline ki-book fs-3 me-2"></i>Data Buku
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold w-150px">Judul Buku</td>
                                        <td><span id="detail-judul_buku">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Penulis</td>
                                        <td><span id="detail-penulis_buku">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Penerbit</td>
                                        <td><span id="detail-penerbit_buku">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tahun Terbit</td>
                                        <td><span id="detail-tahun_terbit">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jenis Buku</td>
                                        <td><span id="detail-jenis_buku">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Bahasa</td>
                                        <td><span id="detail-bahasa_buku">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Estimasi Harga</td>
                                        <td><span id="detail-estimasi_harga">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Link Pembelian</td>
                                        <td><a id="detail-link_pembelian" href="#" target="_blank" class="text-primary">-</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Alasan Usulan</td>
                                        <td><span id="detail-alasan_usulan">-</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                name: "usulan",
                base_url: `{{ route('app.usulan.index') }}`
            });

            // Detail modal handler for usulan buku
            $(document).on('click', '[jf-data="usulan"] [jf-detail]', function() {
                var detailId = $(this).attr('jf-detail');

                ajaxRequest({
                    link: '{{ route('app.usulan.data') }}/detail',
                    data: {
                        reqbuku_id: detailId
                    },
                    callback: function(origin, resp) {
                        var data = resp.data;

                        // Populate detail modal - Buku fields
                        $('#detail-nama_req').text(data.nama_req || '-');
                        $('#detail-email_req').text(data.email_req || '-');

                        // Handle NIM/NIP
                        if (data.nim) {
                            $('#row-nim').show();
                            $('#detail-nim').text(data.nim);
                            $('#row-nip').hide();
                        } else if (data.nip) {
                            $('#row-nip').show();
                            $('#detail-nip').text(data.nip);
                            $('#row-nim').hide();
                        } else {
                            $('#row-nim, #row-nip').hide();
                        }

                        // Program Studi
                        $('#detail-prodi').text(data.prodi_nama || data.prodi?.nama_prodi || '-');

                        $('#detail-judul_buku').text(data.judul_buku || '-');
                        $('#detail-penulis_buku').text(data.penulis_buku || '-');
                        $('#detail-penerbit_buku').text(data.penerbit_buku || '-');
                        $('#detail-tahun_terbit').text(data.tahun_terbit || '-');
                        $('#detail-jenis_buku').text(data.jenis_buku || '-');
                        $('#detail-bahasa_buku').text(data.bahasa_buku || '-');

                        // Format estimasi harga
                        if (data.estimasi_harga) {
                            const formatted = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(data.estimasi_harga);
                            $('#detail-estimasi_harga').text(formatted);
                        } else {
                            $('#detail-estimasi_harga').text('-');
                        }

                        // Handle link pembelian
                        if (data.link_pembelian) {
                            $('#detail-link_pembelian').attr('href', data.link_pembelian).text(
                                'Lihat Link');
                        } else {
                            $('#detail-link_pembelian').attr('href', '#').text('-');
                        }

                        $('#detail-alasan_usulan').text(data.alasan_usulan || '-');

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
                    $('#formReset input[name="reqbuku_id"]').val(detailId);
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
                    link: '{{ route("app.usulan.reset") }}',
                    data: data,
                    swal_success: true,
                    callback: function() {
                        $('[jf-modal="reset"]').modal('hide');
                        $('table[jf-data="usulan"]').DataTable().ajax.reload(null, false);
                    }
                });
            });
        </script>
    @endpush
