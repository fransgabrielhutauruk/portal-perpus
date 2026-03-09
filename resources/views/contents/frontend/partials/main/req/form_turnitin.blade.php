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
                            ['id' => 'tab-dosen', 'label' => '2. Data Dosen', 'active' => false],
                            ['id' => 'tab-dokumen', 'label' => '3. Data Dokumen', 'active' => false],
                        ],
                        'tabsId' => 'turnitinTabs',
                    ])

                    <form id="formTurnitin" action="{{ data_get($content, 'form.action_url') }}" method="POST"
                        enctype="multipart/form-data" data-toggle="validator">
                        @csrf
                        <div class="tab-content mt-5" id="turnitinTabsContent">

                            {{-- === TAB 1: ATTENTION === --}}
                            <div class="tab-pane fade show active text-center" id="tab-attention" role="tabpanel">
                                <div class="border-top border-bottom border-2 py-2">
                                    <h5 class="text-capitalize">Form usulan cek plagiarisme ini diberikan bagi dosen
                                        Politeknik Caltex Riau</h5>
                                </div>

                                <div class="mb-2 mt-3">
                                    <p class="mb-0">Setelah selesai mengisi formulir ini, petugas akan
                                        mengonfirmasikan melalui Email, pastikan Email yang Anda masukkan benar.</p>
                                </div>

                                <div class="contact-form-btn mt-3">
                                    <button type="button" class="btn-default" onclick="switchTab('tab-dosen')">
                                        Selanjutnya
                                    </button>
                                </div>

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
                            <div class="tab-pane fade" id="tab-dosen" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="nama_dosen">
                                                Nama Dosen <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="nama_dosen" id="nama_dosen" class="form-control"
                                                placeholder="Masukkan nama" required
                                                data-error="Nama dosen wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="inisial_dosen">
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
                                            <label class="fw-bold text-muted small text-uppercase" for="email_dosen">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email_dosen" id="email_dosen"
                                                class="form-control" placeholder="Masukkan email" required
                                                data-error="Email tidak valid">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="nip">
                                                NIP <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="nip" id="nip" class="form-control"
                                                placeholder="Masukkan NIP" required data-error="NIP wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2 fw-bold text-muted small text-uppercase">
                                                Program Studi <span class="text-danger">*</span>
                                            </label>
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
                                        onclick="switchTab('tab-attention')">Sebelumnya</button>
                                    <button type="button" class="btn-default"
                                        onclick="validateAndNext('tab-dosen', 'tab-dokumen')">Selanjutnya</button>
                                </div>
                            </div>

                            {{-- === TAB 3: DATA DOKUMEN === --}}
                            <div class="tab-pane fade mt-3" id="tab-dokumen" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mb-2 fw-bold text-muted small text-uppercase">
                                                Jenis Dokumen <span class="text-danger">*</span>
                                            </label>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="jenis_dokumen"
                                                    id="typeSkripsi" value="Karya Ilmiah" required
                                                    data-error="Jenis dokumen wajib dipilih">
                                                <label class="form-check-label" for="typeSkripsi">
                                                    Karya Ilmiah
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="jenis_dokumen"
                                                    id="typeArtikel" value="Proyek Akhir">
                                                <label class="form-check-label" for="typeArtikel">
                                                    Proyek Akhir
                                                </label>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-md-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase"
                                                for="judul_dokumen">
                                                Judul Dokumen <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="judul_dokumen" id="judul_dokumen"
                                                class="form-control"
                                                placeholder="Masukkan judul dokumen (contoh: Analisis Sistem Informasi)"
                                                required data-error="Judul dokumen wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-md-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="file_dokumen">
                                                Upload File Dokumen <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" name="file_dokumen" id="file_dokumen"
                                                class="form-control" accept=".pdf,.doc,.docx" required
                                                data-error="File dokumen wajib diupload">
                                            <small class="text-muted">
                                                <i class="fa-solid fa-info-circle me-1"></i>
                                                Format: .pdf, .doc, .docx | Maksimal 10 MB
                                            </small>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-md-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="keterangan">
                                                Keterangan / Alasan Pengajuan <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="4"
                                                placeholder="Jelaskan alasan atau tujuan pengajuan cek turnitin ini..." required
                                                data-error="Keterangan/alasan pengajuan wajib diisi"></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div class="col-lg-12 mt-4">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                            onclick="switchTab('tab-dosen')">Sebelumnya</button>
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnOpenModal" class="btn-default"
                                                onclick="openConfirmation()">
                                                Kirim Pengajuan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mt-5 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="section-title m-0">
                        <h3 class="wow fadeInUp fs-5">Riwayat Pengajuan Cek Plagiarisme Terbaru</h3>
                    </div>
                    <div class="table-responsive rounded p-4 border border-3">
                        <table class="table table-hover history-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama Dosen</th>
                                    <th>Judul Dokumen</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(data_get($content, 'history', []) as $item)
                                    <tr>
                                        <td>{{ tanggal($item->created_at, ' ') }}</td>
                                        <td>{{ $item->nama_dosen }}</td>
                                        <td>
                                            {{ $item->judul_dokumen }}
                                            <span
                                                class="badge bg-secondary text-light ms-md-2">{{ $item->jenis_dokumen }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status_req == -1)
                                                {!! $item->status_badge !!}
                                                @if ($item->catatan_admin)
                                                    <small class="d-block text-muted mt-1"
                                                        style="font-size: 0.75rem;">[Catatan:
                                                        {{ $item->catatan_admin }}]</small>
                                                @endif
                                            @else
                                                {!! $item->status_badge !!}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data.</td>
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

{{-- === CONFIRMATION MODAL === --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: var(--primary-main)" id="confirmationModalLabel">
                    <i class="fa-solid fa-circle-question me-2"></i>Konfirmasi Pengecekan Plagiarisme
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-secondary fs-6">
                    Apakah Anda yakin data yang diisi sudah benar? <br>
                    Pengajuan yang sudah dikirim tidak dapat diubah kembali.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" style="height: 48px"
                    data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default rounded-pill ps-4 pe-5" style="height: 48px"
                    onclick="submitTurnitinAjax()">Ya, Kirim</button>
            </div>
        </div>
    </div>
</div>

<script>
    function initializeFormValidation() {
        $(document).ready(function() {
            $('#formTurnitin').validator();
        });
    }

    // Start initialization when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFormValidation);
    } else {
        initializeFormValidation();
    }

    function openConfirmation() {
        if (validateTab('tab-dokumen')) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }
    }

    function submitTurnitinAjax() {
        const form = document.getElementById('formTurnitin');
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

    function addHistoryRow(data) {
        const tbody = document.querySelector('.history-table tbody');
        tbody.querySelector('td[colspan="4"]')?.closest('tr').remove();

        const statusBadges = {
            0: '<span class="badge bg-warning text-dark rounded-pill">Menunggu</span>',
            1: '<span class="badge bg-success rounded-pill">Disetujui</span>',
            default: '<span class="badge bg-danger rounded-pill">Ditolak</span>'
        };

        const jenisBadge = `<span class="badge bg-secondary text-light ms-md-2">${data.jenis_dokumen}</span>`;

        tbody.insertAdjacentHTML('afterbegin', `
            <tr class="table-success">
                <td>${data.date_fmt}</td>
                <td>${data.nama_dosen}</td>
                <td>${data.judul_dokumen} ${jenisBadge}</td>
                <td class="text-center">${statusBadges[data.status_req] || statusBadges.default}</td>
            </tr>`);

        setTimeout(() => tbody.querySelector('tr.table-success')?.classList.remove('table-success'), 2000);
    }

    function autofillForm() {
        document.querySelector('input[name="nama_dosen"]').value = "Dr. Ahmad Fauzi, M.Kom";
        document.querySelector('input[name="inisial_dosen"]').value = "AF";
        document.querySelector('input[name="email_dosen"]').value = "frans22si@mahasiswa.pcr.ac.id";
        document.querySelector('input[name="nip"]').value = "19850315201203";
        const prodi = document.querySelector('select[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;
        document.getElementById('typeSkripsi').checked = true;
        document.querySelector('input[name="judul_dokumen"]').value = "Analisis Sistem Informasi Berbasis Web";
        document.querySelector('textarea[name="keterangan"]').value =
            "Mohon dicek plagiarisme untuk dokumen skripsi mahasiswa bimbingan saya.";

        $('#formTurnitin').validator('update');
        $('#formTurnitin').find('.form-control').trigger('input');
    }
</script>
