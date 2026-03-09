<section>
    <div class="container bg-gray-200">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title m-0">
                    <h3 class="wow fadeInUp fs-5">{{ data_get($content, 'subtitle') }}</h3>
                </div>

                <div class="contact-us-form wow fadeInUp rounded px-4 border border-3 pb-md-4" data-wow-delay="0.4s">
                    @include('contents.frontend.partials.components.tab-headers', [
                        'tabs' => [
                            ['id' => 'tab-attention', 'label' => '1. Perhatian', 'active' => true],
                            ['id' => 'tab-user', 'label' => '2. Data Dosen', 'active' => false],
                            ['id' => 'tab-book', 'label' => '3. Data Modul', 'active' => false],
                        ],
                        'tabsId' => 'usulanTabs',
                    ])

                    @if (!data_get($content, 'is_open'))
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fa-solid fa-calendar-xmark fa-4x text-danger"></i>
                            </div>
                            <h4 class="text-danger fw-bold mb-3">Periode Pengajuan Ditutup</h4>
                            <p class="text-muted mb-0">
                                Mohon maaf, saat ini periode pengajuan modul sedang tidak dibuka.
                            </p>
                            <p class="text-muted">
                                Silakan hubungi admin perpustakaan untuk informasi periode selanjutnya.
                            </p>
                        </div>
                    @else
                        <form id="formUsulan" action="{{ data_get($content, 'form.action_url') }}" method="POST"
                            enctype="multipart/form-data" data-toggle="validator">
                            @csrf
                            <div class="tab-content mt-5" id="usulanTabsContent">

                                {{-- === TAB 1: ATTENTION === --}}
                                <div class="tab-pane fade show active text-center" id="tab-attention" role="tabpanel">
                                    <div class="border-top border-bottom border-2 py-2">
                                        <h5 class="text-capitalize">Form usulan modul ini diberikan bagi dosen
                                            Politeknik Caltex Riau</h5>
                                    </div>
                                    <div class="d-inline-block text-center border-0 pt-3 px-4 rounded-3 mt-2">
                                        <p class="mb-0 text-danger">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Batas Tanggal
                                            Usulan
                                            <strong>{{ data_get($content, 'periode_name') }} </strong> Adalah
                                            <strong>{{ tanggal(data_get($content, 'active_periode.tanggal_selesai'), ' ') }}</strong>
                                        </p>
                                    </div>

                                    @if (data_get($content, 'is_open'))
                                        <div class="contact-form-btn mt-3">
                                            <button type="button" class="btn-default" onclick="switchTab('tab-user')">
                                                Selanjutnya
                                            </button>
                                        </div>
                                    @endif

                                    @env('local')
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill"
                                                onclick="autofillForm()">
                                                <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Demo Autofill
                                            </button>
                                        </div>
                                    @endenv
                                </div>

                                {{-- === TAB 2: DATA DOSEN === --}}
                                <div class="tab-pane fade" id="tab-user" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="nama_dosen">Nama
                                                    Dosen <span class="text-danger">*</span></label>
                                                <input type="text" name="nama_dosen" id="nama_dosen"
                                                    class="form-control" placeholder="Masukkan nama" required
                                                    data-error="Nama dosen wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="inisial_dosen">
                                                    Inisial <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="inisial_dosen" id="inisial_dosen"
                                                    class="form-control" placeholder="Contoh: AAA" required
                                                    data-error="Inisial wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="email_dosen">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email_dosen" id="email_dosen"
                                                    class="form-control" placeholder="Masukkan email" required
                                                    data-error="Email tidak valid">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="nip">NIP
                                                    <span class="text-danger">*</span></label>
                                                <input type="number" name="nip" id="nip" class="form-control"
                                                    placeholder="Masukkan NIP" required data-error="NIP wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Program
                                                    Studi
                                                    <span class="text-danger">*</span></label>
                                                <select name="prodi_id" class="form-select" required
                                                    data-error="Pilih program studi">
                                                    <option value="">-- Pilih Program Studi --</option>
                                                    @foreach (data_get($content, 'prodi_list', []) as $prodi)
                                                        <option value="{{ $prodi->prodi_id }}">{{ $prodi->nama_prodi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                            data-bs-dismiss="modal"
                                            onclick="switchTab('tab-attention')">Sebelumnya</button>
                                        <button type="button" class="btn-default"
                                            onclick="validateAndNext('tab-user', 'tab-book')">Selanjutnya</button>
                                    </div>
                                </div>

                                {{-- === TAB 3: DATA MODUL === --}}
                                <div class="tab-pane fade mt-3" id="tab-book" role="tabpanel">
                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="nama_mata_kuliah">
                                                    Nama Mata Kuliah <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nama_mata_kuliah" id="nama_mata_kuliah"
                                                    class="form-control" placeholder="Masukkan nama mata kuliah"
                                                    required data-error="Nama mata kuliah wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="judul_modul">
                                                    Judul Modul <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="judul_modul" id="judul_modul"
                                                    class="form-control" placeholder="Masukkan judul modul" required
                                                    data-error="Judul modul wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="penulis_modul">
                                                    Penulis Modul <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="penulis_modul" id="penulis_modul"
                                                    class="form-control" placeholder="Masukkan nama penulis" required
                                                    data-error="Penulis modul wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="tahun_modul">
                                                    Tahun Modul <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="tahun_modul" id="tahun_modul"
                                                    class="form-control" placeholder="YYYY" required
                                                    data-error="Tahun modul wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="fw-bold text-muted small text-uppercase d-block mb-3">Jenis
                                                    Modul <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="praktikum"
                                                        id="typeTeori" value="0" required
                                                        data-error="Jenis modul wajib dipilih">
                                                    <label class="form-check-label" for="typeTeori">Teori</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="praktikum"
                                                        id="typePraktikum" value="1">
                                                    <label class="form-check-label"
                                                        for="typePraktikum">Praktikum</label>
                                                </div>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="jumlah_dibutuhkan">
                                                    Jumlah Dibutuhkan <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="jumlah_dibutuhkan" id="jumlah_dibutuhkan"
                                                    class="form-control" placeholder="Masukkan jumlah yang dibutuhkan"
                                                    min="1" required
                                                    data-error="Jumlah dibutuhkan wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="fw-bold text-muted small text-uppercase d-block mb-3">Apakah
                                                    Ada Revisi atau Cetak Baru? <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="ada_revisi"
                                                        id="revisiTidak" value="0" onchange="toggleFileUpload()"
                                                        required data-error="Pilihan ini wajib diisi">
                                                    <label class="form-check-label" for="revisiTidak">Tidak</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="ada_revisi"
                                                        id="revisiYa" value="1" onchange="toggleFileUpload()">
                                                    <label class="form-check-label" for="revisiYa">Ya</label>
                                                </div>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-md-4" id="fileUploadSection" style="display: none;">
                                        <label class="fw-bold text-muted small text-uppercase" for="file_modul">
                                            Upload File Modul <span class="text-danger">*</span>
                                        </label>
                                        <input type="file" name="file" id="file_modul" class="form-control"
                                            accept=".pdf,.docx">
                                        <small class="text-muted">Format: PDF/DOCX. Upload file modul yang perlu
                                            direvisi atau dicetak ulang.</small>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group my-4">
                                        <label class="fw-bold text-muted small text-uppercase"
                                            for="deskripsi_kebutuhan">
                                            Deskripsi Kebutuhan
                                        </label>
                                        <textarea name="deskripsi_kebutuhan" id="deskripsi_kebutuhan" class="form-control" rows="3"
                                            placeholder="Jelaskan detail kebutuhan atau revisi..."></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    {{-- ACTION BUTTONS --}}
                                    <div class="col-lg-12 mt-4">
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                                data-bs-dismiss="modal"
                                                onclick="switchTab('tab-user')">Sebelumnya</button>
                                            <button type="button" id="btnOpenModal" class="btn-default"
                                                onclick="openConfirmation()">
                                                Kirim Permintaan
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>

                {{-- --- HISTORY TABLE --- --}}
                <div class="mt-5 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="section-title m-0">
                        <h3 class="wow fadeInUp fs-5">Riwayat Pengajuan Kebutuhan Modul Terbaru</h3>
                    </div>
                    <div class="table-responsive rounded p-4 border border-3">
                        <div class="table-responsive">
                            <table class="table table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Pengusul</th>
                                        <th>Mata Kuliah</th>
                                        <th>Modul</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(data_get($content, 'history', []) as $item)
                                        <tr>
                                            <td>{{ tanggal($item->created_at, ' ') }}</td>
                                            <td>{{ $item->nama_dosen }}</td>
                                            <td>
                                                {{ $item->nama_mata_kuliah }}
                                                @if ($item->praktikum)
                                                    <span
                                                        class="badge bg-secondary text-light ms-md-2">Praktikum</span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary text-light border ms-md-2">Teori</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->judul_modul }}</td>
                                            <td class="text-center">
                                                @if ($item->status_req == -1)
                                                    {!! $item->status_badge !!}
                                                    @if ($item->catatan_admin)
                                                        <small class="d-block text-muted mt-1"
                                                            style="font-size: 0.75rem;">[Alasan:
                                                            {{ $item->catatan_admin }}]</small>
                                                    @endif
                                                @else
                                                    {!! $item->status_badge !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Belum ada data request.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

{{-- --- 3. CONFIRMATION MODAL --- --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: var(--primary-main)" id="confirmationModalLabel">
                    <i class="fa-solid fa-circle-question me-2"></i>Konfirmasi Pengajuan Modul
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-secondary fs-6">
                    Apakah Anda yakin data modul yang diisi sudah benar? <br>
                    Pengajuan yang sudah dikirim tidak dapat diubah kembali.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" style="height: 48px"
                    data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default rounded-pill ps-4 pe-5" style="height: 48px"
                    onclick="submitUsulanAjax()">Ya, Kirim</button>
            </div>
        </div>
    </div>
</div>

{{-- B. Custom Logic --}}
<script>
    function initializeFormValidation() {
        $(document).ready(function() {
            $('#formUsulan').validator();
        });
    }

    // Start initialization when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFormValidation);
    } else {
        initializeFormValidation();
    }

    function openConfirmation() {
        if (validateTab('tab-book')) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }
    }

    function submitUsulanAjax() {
        const form = document.getElementById('formUsulan');
        const submitBtn = document.getElementById('btnOpenModal');

        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
        modal.hide();

        // Show loading with SweetAlert
        Swal.fire({
            html: 'Mengirim data...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        submitBtn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server mengembalikan response tidak valid');
                }
                return res.json().then(data => ({
                    status: res.status,
                    body: data
                }));
            })
            .then(({
                status,
                body
            }) => {
                submitBtn.disabled = false;

                if (status === 200 || status === 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: body.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745',
                        allowOutsideClick: false
                    }).then(() => {
                        form.reset();
                        if (body.new_data) addHistoryRow(body.new_data);
                        switchTab('tab-attention');
                    });
                } else {
                    const errorMsg = body.errors ?
                        Object.values(body.errors).flat().join('\n') :
                        body.message || 'Terjadi kesalahan.';

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                submitBtn.disabled = false;

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan jaringan.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            });
    }

    function addHistoryRow(data) {
        const tbody = document.querySelector('.history-table tbody');
        tbody.querySelector('td[colspan="5"]')?.closest('tr').remove();

        const jenisBadge = data.praktikum == 1 ?
            '<span class="badge bg-secondary text-light ms-md-2">Praktikum</span>' :
            '<span class="badge bg-secondary text-light border ms-md-2">Teori</span>';

        const statusBadges = {
            0: '<span class="badge bg-warning text-dark rounded-pill">Menunggu</span>',
            1: '<span class="badge bg-success rounded-pill">Disetujui</span>',
            default: '<span class="badge bg-danger rounded-pill">Ditolak</span>'
        };

        tbody.insertAdjacentHTML('afterbegin', `
            <tr class="table-success">
                <td>${data.date_fmt}</td>
                <td>${data.nama_dosen}</td>
                <td>${data.nama_mata_kuliah} ${jenisBadge}</td>
                <td>${data.judul_modul}</td>
                <td class="text-center">${statusBadges[data.status_req] || statusBadges.default}</td>
            </tr>`);

        setTimeout(() => tbody.querySelector('tr.table-success')?.classList.remove('table-success'), 2000);
    }

    // ============================================
    // VALIDATION
    // ============================================
    function validateTab(tabId) {
        const $tab = $(`#${tabId}`);
        const $inputs = $tab.find('input:visible, select:visible, textarea:visible');
        let isValid = true;

        $inputs.each(function() {
            const $el = $(this);
            if (!this.checkValidity()) isValid = false;
            $el.trigger('input');
            if ($el.closest('.form-group').hasClass('has-error')) isValid = false;
        });

        if (!isValid) {
            const $firstError = $tab.find('.has-error').first();
            if ($firstError.length) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 500);
            }
        }
        return isValid;
    }

    function validateAndNext(currentTabId, nextTabId) {
        if (validateTab(currentTabId)) switchTab(nextTabId);
    }

    // ============================================
    // FORM FIELD HELPERS
    // ============================================
    function switchTab(targetId) {
        const triggerEl = document.querySelector(`button[data-bs-target="#${targetId}"]`);
        triggerEl.disabled = false;
        const tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }

    function toggleFileUpload() {
        const revisiYa = document.getElementById('revisiYa');
        const fileUploadSection = document.getElementById('fileUploadSection');
        const fileInput = document.getElementById('file_modul');

        if (revisiYa.checked) {
            fileUploadSection.style.display = 'block';
            fileInput.setAttribute('required', 'required');
        } else {
            fileUploadSection.style.display = 'none';
            fileInput.removeAttribute('required');
            fileInput.value = '';
        }

        $('#formUsulan').validator('update');
    }

    function autofillForm() {
        document.querySelector('input[name="nama_dosen"]').value = "Budi Santoso, M.T.";
        document.querySelector('input[name="inisial_dosen"]').value = "BS";
        document.querySelector('input[name="email_dosen"]').value = "budi@pcr.ac.id";
        document.querySelector('input[name="nip"]').value = "19880101202001";
        const prodi = document.querySelector('select[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;
        document.querySelector('input[name="nama_mata_kuliah"]').value = "Pemrograman Web";
        document.querySelector('input[name="judul_modul"]').value = "Modul Praktikum Laravel 11";
        document.querySelector('input[name="penulis_modul"]').value = "Tim Dosen Web";
        document.querySelector('input[name="tahun_modul"]').value = "2025";
        document.querySelector('input[name="jumlah_dibutuhkan"]').value = "40";
        document.getElementById('typePraktikum').checked = true;
        document.querySelector('textarea[name="deskripsi_kebutuhan"]').value =
            "Mohon dicetak warna untuk bagian diagram.";

        $('#formUsulan').validator('update');
        $('#formUsulan').find('.form-control').trigger('input');
    }
</script>
