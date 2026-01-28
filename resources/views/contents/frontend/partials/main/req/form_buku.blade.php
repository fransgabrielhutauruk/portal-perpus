<style>
    @media (max-width: 768px) {

        /* Reduce padding on form container */
        .contact-us-form {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        /* Tab headers responsive */
        .nav-tabs {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            font-size: 0.85rem;
        }

        .nav-tabs .nav-link {
            padding: 0.5rem 0.25rem;
        }

        /* Form spacing */
        .tab-content {
            margin-top: 2rem !important;
        }

        /* Reduce form group margins */
        .form-group {
            margin-bottom: 1rem;
        }

        /* Penerbit checkboxes - stack vertically on mobile */
        .row .col-md-4 {
            margin-bottom: 0.75rem;
        }

        /* Buttons full width on mobile */
        .btn-default,
        .btn-outline-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
        }

        /* Alert/info boxes */
        .d-inline-block {
            display: block !important;
            width: 100%;
        }

        /* Table responsive */
        .table-responsive {
            font-size: 0.875rem;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }

        /* Reduce heading sizes */
        h5 {
            font-size: 1rem;
        }

        h3 {
            font-size: 1.25rem;
        }

        /* Form labels */
        label {
            font-size: 0.875rem;
        }

        /* Checkbox/Radio inline - stack on small screens */
        @media (max-width: 576px) {
            .form-check-inline {
                display: block;
                margin-bottom: 0.5rem;
            }
        }
    }

    /* Additional tweaks for very small screens */
    @media (max-width: 576px) {
        .border-3 {
            border-width: 2px !important;
        }

        .py-3 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .px-5 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }
</style>

<section>
    <div class="container bg-gray-200">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title m-0">
                    <h3 class="wow fadeInUp fs-5">{{ data_get($content, 'subtitle') }}</h3>
                </div>

                <div class="contact-us-form wow fadeInUp rounded px-4 border border-3" data-wow-delay="0.4s">
                    @include('contents.frontend.partials.components.tab-headers', [
                        'tabs' => [
                            ['id' => 'tab-attention', 'label' => '1. Perhatian', 'active' => true],
                            ['id' => 'tab-user', 'label' => '2. Data Pengusul', 'active' => false],
                            ['id' => 'tab-book', 'label' => '3. Data Buku', 'active' => false],
                        ],
                        'tabsId' => 'usulanTabs',
                    ])

                    @if (!data_get($content, 'is_open'))
                        {{-- PERIODE TUTUP --}}
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fa-solid fa-calendar-xmark fa-4x text-danger"></i>
                            </div>
                            <h4 class="text-danger fw-bold mb-3">Periode Pengajuan Ditutup</h4>
                            <p class="text-muted mb-0">
                                Mohon maaf, saat ini periode pengajuan usulan buku sedang tidak dibuka.
                            </p>
                            <p class="text-muted">
                                Silakan hubungi admin perpustakaan untuk informasi periode selanjutnya.
                            </p>
                        </div>
                    @else
                        {{-- PERIODE BUKA --}}
                        <form id="formUsulan" action="{{ data_get($content, 'form.action_url') }}" method="POST"
                            data-toggle="validator">
                            @csrf
                            <div class="tab-content mt-5" id="usulanTabsContent">
                                <div class="tab-pane fade show active text-center" id="tab-attention" role="tabpanel">
                                    <div class="border-top border-bottom border-2 py-2">
                                        <h5 class="text-capitalize">Form usulan buku ini diberikan bagi sivitas
                                            akademika Politeknik Caltex
                                            Riau</h5>
                                    </div>
                                    <div class="d-inline-block text-center border-0 pt-3 px-4 rounded-3 mt-2">
                                        <p class="mb-0 text-danger">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Batas Tanggal Usulan
                                            <strong>{{ data_get($content, 'periode_name') }} </strong> Adalah
                                            <strong>{{ tanggal(data_get($content, 'active_periode.tanggal_selesai'), ' ') }}</strong>
                                        </p>
                                    </div>

                                    <div class="mb-2 mt-3">
                                        <p class="mb-0">Pastikan Anda telah melakukan pengecekan ketersediaan buku
                                            melalui <a href="{{ data_get($content, 'opac_url', '#') }}" target="_blank"
                                                class="fw-bold text-decoration-underline"
                                                style="color: var(--primary-main)">OPAC</a>
                                            sebelum
                                            melanjutkan.</p>
                                    </div>

                                    <div class="mt-4 mb-2 d-flex justify-content-center">
                                        <div class="d-inline-block border border-2 py-2 px-5 rounded text-start">
                                            <div class="form-check">
                                                <input class="form-check-input border-dark" type="checkbox"
                                                    id="agreementCheck" onchange="toggleNextButton()">
                                                <label class="form-check-label" for="agreementCheck">
                                                    Saya sudah memeriksa OPAC dan buku belum tersedia.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="checkboxFeedback" class="text-danger small mt-2 d-none">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                                        Silakan centang persetujuan terlebih dahulu
                                    </div>

                                    @if (data_get($content, 'is_open'))
                                        <div class="contact-form-btn mt-3">
                                            <button type="button" id="btnToStep2" class="btn-default"
                                                onclick="proceedToNextTab()">
                                                Selanjutnya
                                            </button>
                                        </div>
                                    @endif

                                    <div class="d-flex">
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill"
                                            onclick="autofillForm()">
                                            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Demo Autofill
                                        </button>
                                    </div>
                                </div>

                                {{-- === TAB 2: DATA PENGUSUL === --}}
                                <div class="tab-pane fade" id="tab-user" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="nama_req">Nama
                                                    Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" name="nama_req" id="nama_req"
                                                    class="form-control" placeholder="Masukkan nama lengkap" required
                                                    data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="email_req">Email
                                                    PCR <span class="text-danger">*</span></label>
                                                <input type="email" name="email_req" id="email_req"
                                                    class="form-control" placeholder="Masukkan Email PCR" required
                                                    data-error="Email tidak valid">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Tipe
                                                    Identitas</label>
                                                <select class="form-select" id="identity_type"
                                                    onchange="toggleIdentity(this.value)">
                                                    <option value="nim">Mahasiswa (NIM)</option>
                                                    <option value="nip">Dosen/Staff (NIP)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase">Nomor
                                                    Identitas <span class="text-danger">*</span></label>
                                                <input type="number" name="nim" id="input_nim" class="form-control"
                                                    placeholder="Masukkan NIM" required data-error="NIM Wajib diisi">
                                                <input type="number" name="nip" id="input_nip"
                                                    class="form-control" placeholder="Masukkan NIP"
                                                    style="display:none;" data-error="NIP Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Program
                                                    Studi <span class="text-danger">*</span></label>
                                                <select name="prodi_id" class="form-select" required
                                                    data-error="Pilih Program Studi">
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
                                            data-bs-dismiss="modal" onclick="switchTab('tab-attention')">Sebelumnya
                                        </button>
                                        <button type="button" class="btn-default"
                                            onclick="validateAndNext('tab-user', 'tab-book')">Selanjutnya</button>
                                    </div>
                                </div>

                                {{-- === TAB 3: DATA BUKU === --}}
                                <div class="tab-pane fade mt-3" id="tab-book" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="judul_buku">Judul
                                                    Buku <span class="text-danger">*</span></label>
                                                <input type="text" name="judul_buku" id="judul_buku"
                                                    class="form-control" placeholder="Masukkan judul buku" required
                                                    data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="penulis_buku">Penulis <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="penulis_buku" id="penulis_buku"
                                                    class="form-control" placeholder="Masukkan penulis" required
                                                    data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="tahun_terbit">Tahun
                                                    Terbit (Wajib 5 Tahun Terakhir) <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="tahun_terbit" id="tahun_terbit"
                                                    class="form-control" placeholder="Masukkan tahun terbit" required
                                                    data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="fw-bold text-muted small text-uppercase d-block mb-2">Bahasa
                                                    Pengantar Buku <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="bahasa_buku"
                                                        id="bahasa_indonesia" value="indonesia" required>
                                                    <label class="form-check-label" for="bahasa_indonesia">
                                                        Indonesia
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="bahasa_buku"
                                                        id="bahasa_inggris" value="inggris" required>
                                                    <label class="form-check-label" for="bahasa_inggris">
                                                        Inggris
                                                    </label>
                                                </div>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="fw-bold text-muted small text-uppercase d-block mb-2">Penerbit
                                                    <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input penerbit-checkbox" type="checkbox"
                                                        name="penerbit[]" id="penerbit_graha" value="Graha Ilmu">
                                                    <label class="form-check-label" for="penerbit_graha">Graha
                                                        Ilmu</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input penerbit-checkbox" type="checkbox"
                                                        name="penerbit[]" id="penerbit_salemba" value="Salemba">
                                                    <label class="form-check-label"
                                                        for="penerbit_salemba">Salemba</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input penerbit-checkbox" type="checkbox"
                                                        name="penerbit[]" id="penerbit_informatika"
                                                        value="Informatika">
                                                    <label class="form-check-label"
                                                        for="penerbit_informatika">Informatika</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input penerbit-checkbox" type="checkbox"
                                                        name="penerbit[]" id="penerbit_andi" value="Andi">
                                                    <label class="form-check-label" for="penerbit_andi">Andi</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input penerbit-checkbox" type="checkbox"
                                                        name="penerbit[]" id="penerbit_elsevier"
                                                        value="E-Book Elsevier">
                                                    <label class="form-check-label" for="penerbit_elsevier">E-Book
                                                        Elsevier</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input penerbit-checkbox" type="checkbox"
                                                        name="penerbit[]" id="penerbit_pearson" value="Pearson">
                                                    <label class="form-check-label"
                                                        for="penerbit_pearson">Pearson</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="penerbit_other" onchange="togglePenerbitOther()">
                                                    <label class="form-check-label" for="penerbit_other">Other</label>
                                                </div>
                                                <input type="text" name="penerbit[]" id="penerbit_other_text"
                                                    class="form-control" placeholder="Nama penerbit lainnya..."
                                                    style="display: none;">
                                                <div id="penerbit-error" class="text-danger small mt-1 d-none">
                                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                    Pilih minimal satu penerbit
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="fw-bold text-muted small text-uppercase d-block mb-2">Jenis
                                                    Buku <span class="text-danger">*</span></label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input jenis-buku-checkbox"
                                                        type="checkbox" name="jenis_buku[]" id="jenis_referensi"
                                                        value="referensi perkuliahan">
                                                    <label class="form-check-label" for="jenis_referensi">
                                                        Referensi Perkuliahan
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input jenis-buku-checkbox"
                                                        type="checkbox" name="jenis_buku[]" id="jenis_hobi"
                                                        value="hobi">
                                                    <label class="form-check-label" for="jenis_hobi">
                                                        Hobi
                                                    </label>
                                                </div>
                                                <div id="jenis-buku-error" class="text-danger small mt-1 d-none">
                                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                    Pilih minimal satu jenis buku
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="estimasi_harga">Estimasi
                                                    Harga (Jika Ada)</label>
                                                <input type="number" name="estimasi_harga" id="estimasi_harga"
                                                    placeholder="Masukkan estimasi harga" class="form-control"
                                                    data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold text-muted small text-uppercase"
                                                for="link_pembelian">Link
                                                Pembelian <span class="text-danger">*</span></label>
                                            <input type="text" name="link_pembelian" id="link_pembelian"
                                                class="form-control" placeholder="https://..." required
                                                data-error="Wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="form-group my-4">
                                        <label class="fw-bold text-muted small text-uppercase"
                                            for="alasan_usulan">Alasan
                                            Usulan <span class="text-danger">*</span></label>
                                        <textarea name="alasan_usulan" id="alasan_usulan" class="form-control" rows="1"
                                            placeholder="Masukkan alasan usulan" required data-error="Wajib diisi"></textarea>
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
                                                Kirim Usulan
                                            </button>
                                        </div>

                                        {{-- Loading & Messages --}}
                                        <div class="mt-3 text-center">
                                            <span id="loadingIndicator" class="text-primary d-none fw-bold">
                                                <i class="fa-solid fa-spinner fa-spin me-2"></i> Mengirim data...
                                            </span>
                                            <div id="msgSubmit" class="alert d-none mt-3"></div>
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
                        <h3 class="wow fadeInUp fs-5">Riwayat Usulan Buku Terbaru</h3>
                    </div>
                    <div class="table-responsive rounded p-4 border border-3">
                        <table class="table table-hover history-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Usulan</th>
                                    <th>Pengusul</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(data_get($content, 'history', []) as $item)
                                    <tr>
                                        <td>{{ tanggal($item->created_at, ' ') }}</td>
                                        <td>{{ $item->nama_req }}</td>
                                        <td>{{ $item->judul_buku }}</td>
                                        <td>{{ $item->penulis_buku }}</td>
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
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data.
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
                    <i class="fa-solid fa-circle-question me-2"></i>Konfirmasi Pengusulan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-secondary fs-6">
                    Apakah Anda yakin data yang diisi sudah benar? <br>
                    Usulan yang sudah dikirim tidak dapat diubah kembali.
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

<script>
    $(document).ready(function() {
        $('#formUsulan').validator();

        $('.jenis-buku-checkbox').on('change', function() {
            const anyChecked = $('.jenis-buku-checkbox:checked').length > 0;
            const errorElement = $('#jenis-buku-error');
            anyChecked ? errorElement.addClass('d-none') : errorElement.removeClass('d-none');
        });

        $('.penerbit-checkbox').on('change', function() {
            const anyChecked = $('.penerbit-checkbox:checked').length > 0 || $('#penerbit_other_text')
                .val().trim();
            const errorElement = $('#penerbit-error');
            anyChecked ? errorElement.addClass('d-none') : errorElement.removeClass('d-none');
        });

        $('#penerbit_other_text').on('input', function() {
            const anyChecked = $('.penerbit-checkbox:checked').length > 0 || $(this).val().trim();
            const errorElement = $('#penerbit-error');
            anyChecked ? errorElement.addClass('d-none') : errorElement.removeClass('d-none');
        });
    });

    function openConfirmation() {
        console.log('tes');
        if (validateTab('tab-book')) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
            console.log('tes2');
        }
        console.log('tes3');
    }

    function submitUsulanAjax() {
        const form = document.getElementById('formUsulan');
        const submitBtn = document.getElementById('btnOpenModal');
        const loading = document.getElementById('loadingIndicator');

        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
        modal.hide();

        loading.classList.remove('d-none');
        submitBtn.disabled = true;

        const formData = new FormData(form);
        const jsonData = {};

        for (const [key, value] of formData.entries()) {
            if (key.endsWith('[]')) {
                const cleanKey = key.slice(0, -2);
                if (!jsonData[cleanKey]) jsonData[cleanKey] = [];
                jsonData[cleanKey].push(value);
            } else {
                jsonData[key] = value;
            }
        }

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
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
                loading.classList.add('d-none');
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
                loading.classList.add('d-none');
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

        const statusBadges = {
            0: '<span class="badge bg-warning text-dark rounded-pill">Menunggu</span>',
            1: '<span class="badge bg-success rounded-pill">Disetujui</span>',
            default: '<span class="badge bg-danger rounded-pill">Ditolak</span>'
        };

        tbody.insertAdjacentHTML('afterbegin', `
            <tr class="table-success">
                <td>${data.date_fmt}</td>
                <td>${data.nama_req}</td>
                <td>${data.judul_buku}</td>
                <td>${data.penulis_buku}</td>
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

        if (tabId === 'tab-book') {
            const hasJenisBuku = $tab.find('.jenis-buku-checkbox:checked').length > 0;
            $('#jenis-buku-error').toggleClass('d-none', hasJenisBuku);
            if (!hasJenisBuku) isValid = false;

            const hasPenerbit = $tab.find('.penerbit-checkbox:checked').length > 0 ||
                $('#penerbit_other_text').val().trim();
            $('#penerbit-error').toggleClass('d-none', hasPenerbit);
            if (!hasPenerbit) isValid = false;
        }

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
    function toggleIdentity(type) {
        const nim = document.getElementById('input_nim');
        const nip = document.getElementById('input_nip');
        const isNim = type === 'nim';

        nim.style.display = isNim ? 'block' : 'none';
        nip.style.display = isNim ? 'none' : 'block';

        isNim ? nim.setAttribute('required', 'required') : nim.removeAttribute('required');
        isNim ? nip.removeAttribute('required') : nip.setAttribute('required', 'required');

        (isNim ? nip : nim).value = '';
        $('#formUsulan').validator('update');
    }

    function togglePenerbitOther() {
        const checkbox = document.getElementById('penerbit_other');
        const input = document.getElementById('penerbit_other_text');

        input.style.display = checkbox.checked ? 'block' : 'none';
        if (checkbox.checked) input.focus();
        else input.value = '';
    }

    function toggleNextButton() {
        const checkbox = document.getElementById('agreementCheck');
        const btn = document.getElementById('btnToStep2');
        const feedback = document.getElementById('checkboxFeedback');

        btn?.classList.toggle('opacity-50', !checkbox?.checked);
        if (checkbox?.checked) feedback?.classList.add('d-none');
    }

    function proceedToNextTab() {
        const checkbox = document.getElementById('agreementCheck');
        const feedback = document.getElementById('checkboxFeedback');

        if (!checkbox.checked) {
            feedback.classList.remove('d-none');
            feedback.style.animation = 'shake 2s';
            setTimeout(() => feedback.style.animation = '', 500);
            return;
        }

        switchTab('tab-user');
    }

    function autofillForm() {
        document.getElementById('agreementCheck').checked = true;
        toggleNextButton();
        document.querySelector('input[name="nama_req"]').value = "Mahasiswa Teladan";
        document.querySelector('input[name="email_req"]').value = "mahasiswa@pcr.ac.id";
        document.getElementById('identity_type').value = 'nim';
        toggleIdentity('nim');
        document.querySelector('input[name="nim"]').value = "2355301999";
        const prodi = document.querySelector('select[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;
        document.querySelector('input[name="judul_buku"]').value = "Clean Code";
        document.querySelector('input[name="penulis_buku"]').value = "Robert C. Martin";
        document.querySelector('input[name="tahun_terbit"]').value = "2008";
        document.getElementById('penerbit_pearson').checked = true;
        document.querySelector('input[name="estimasi_harga"]').value = "450000";
        document.querySelector('input[name="link_pembelian"]').value = "https://amazon.com";
        document.getElementById('jenis_referensi').checked = true;
        document.getElementById('bahasa_inggris').checked = true;
        document.querySelector('textarea[name="alasan_usulan"]').value = "Referensi Skripsi";

        $('#formUsulan').validator('update');
        $('#formUsulan').find('.form-control').trigger('input');
    }
</script>
