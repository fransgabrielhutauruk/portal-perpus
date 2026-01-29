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
                            [
                                'id' => 'tab-attention',
                                'label' => '1. Perhatian',
                                'active' => true,
                                'disabled' => false,
                            ],
                            [
                                'id' => 'tab-requirements',
                                'label' => '2. Konfirmasi Syarat',
                                'active' => false,
                                'disabled' => false,
                            ],
                            [
                                'id' => 'tab-user',
                                'label' => '3. Data Mahasiswa',
                                'active' => false,
                                'disabled' => false,
                            ],
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
                                Mohon maaf, saat ini periode pengajuan bebas pustaka sedang tidak dibuka.
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

                                {{-- === TAB 1: ATTENTION === --}}
                                <div class="tab-pane fade show active text-center" id="tab-attention" role="tabpanel">
                                    <div class="border-top border-bottom border-2 py-2">
                                        <h5>Pengajuan Surat Bebas Pustaka Ini Ditujukan Kepada Mahasiswa
                                            yang Yudisium</h5>
                                    </div>
                                    <div class="d-inline-block text-center border-0 pt-3 px-4 rounded-3 mt-2">
                                        <p class="mb-0 text-danger">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Batas Tanggal
                                            Usulan
                                            <strong>{{ data_get($content, 'periode_name') }} </strong> Adalah
                                            <strong>{{ tanggal(data_get($content, 'active_periode.tanggal_selesai'), ' ') }}</strong>
                                        </p>
                                    </div>
                                    <div class="mb-2 mt-3">
                                        <p class="mb-0">Setelah Selesai Mengisi Formulir Ini, Petugas Akan
                                            Mengonfirmasikan Melalui Email, Pastikan Email yang Anda Masukkan Benar.</p>
                                    </div>

                                    @if (data_get($content, 'is_open'))
                                        <div class="contact-form-btn mt-3">
                                            <button type="button" id="btnToStep2" class="btn-default"
                                                onclick="switchTab('tab-requirements')">
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

                                {{-- === TAB 2: KONFIRMASI SYARAT === --}}
                                <div class="tab-pane fade" id="tab-requirements" role="tabpanel">
                                    <div class="p-4 bg-light border rounded mb-4">
                                        <h5 class="mb-2" style="color: var(--primary-main)"><i
                                                class="fa-solid fa-list-check me-2"></i> Konfirmasi
                                            Syarat Bebas Pustaka</h5>
                                        <p class="text-muted mb-3">Penuhi semua syarat di bawah ini</p>
                                        @php
                                            $syarat = [
                                                [
                                                    'key' => 'check_kp',
                                                    'label' => 'Sudah upload file Kerja Praktek (KP) di repository?',
                                                ],
                                                [
                                                    'key' => 'check_hardcopy_kp',
                                                    'label' => 'Sudah mengumpulkan Hard File KP?',
                                                ],
                                                [
                                                    'key' => 'check_pa',
                                                    'label' => 'Sudah upload file Proyek Akhir (PA) di repository?',
                                                ],
                                                [
                                                    'key' => 'check_hardcopy_pa',
                                                    'label' => 'Sudah mengumpulkan Hard File PA?',
                                                ],
                                                [
                                                    'key' => 'check_buku',
                                                    'label' => 'Sudah menyelesaikan semua pengembalian buku?',
                                                ],
                                                [
                                                    'key' => 'check_modul',
                                                    'label' => 'Sudah mengembalikan modul semester?',
                                                ],
                                            ];
                                        @endphp

                                        <div id="requirements-container">
                                            @foreach ($syarat as $item)
                                                <div class="form-group row align-items-center mb-2 pb-2 border-bottom border-light req-item"
                                                    data-key="{{ $item['key'] }}">
                                                    <label
                                                        class="col-md-8 col-form-label fw-bold text-dark small">{{ $item['label'] }}</label>
                                                    <div class="col-md-4 text-md-end">
                                                        <div class="btn-group w-100" role="group">
                                                            <input type="radio" class="btn-check"
                                                                name="{{ $item['key'] }}" id="{{ $item['key'] }}_yes"
                                                                value="1">
                                                            <label class="btn btn-outline-success btn-sm"
                                                                for="{{ $item['key'] }}_yes">Ya</label>

                                                            <input type="radio" class="btn-check"
                                                                name="{{ $item['key'] }}" id="{{ $item['key'] }}_no"
                                                                value="0">
                                                            <label class="btn btn-outline-danger btn-sm"
                                                                for="{{ $item['key'] }}_no">Tidak</label>
                                                        </div>
                                                        <div
                                                            class="text-danger small mt-1 d-none error-msg text-start fw-bold">
                                                            <i class="fa-solid fa-circle-exclamation"></i> Syarat ini
                                                            wajib
                                                            terpenuhi (Ya).
                                                        </div>
                                                    </div>

                                                    @if ($item['key'] === 'check_kp' || $item['key'] === 'check_pa')
                                                        <div class="col-12 mt-2 repository-link-container d-none"
                                                            id="{{ $item['key'] }}_link_container">
                                                            <div class="form-group mb-0">
                                                                <label class="fw-bold text-muted small"
                                                                    for="{{ $item['key'] }}_link">
                                                                    <i class="fa-solid fa-link me-1"></i> Link
                                                                    Repository
                                                                    @if ($item['key'] === 'check_kp')
                                                                        (KP)
                                                                    @else
                                                                        (PA)
                                                                    @endif
                                                                </label>
                                                                <input type="url"
                                                                    name="{{ $item['key'] === 'check_kp' ? 'link_kp_repository' : 'link_pa_repository' }}"
                                                                    id="{{ $item['key'] }}_link"
                                                                    class="form-control form-control-sm repository-link-input"
                                                                    placeholder="Cth: https://repository.lib.pcr.ac.id/id/eprint/..."
                                                                    data-error="URL repository tidak valid">
                                                                <small class="text-muted">Masukkan link detail file
                                                                    sistem repository Anda</small>
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- ACTION BUTTONS --}}
                                    <div class="col-lg-12 mt-4">
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                                onclick="switchTab('tab-attention')">Sebelumnya</button>
                                            <button type="button" class="btn-default"
                                                onclick="validateAndNext('tab-requirements', 'tab-user')">
                                                Selanjutnya
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- === TAB 3: DATA MAHASISWA === --}}
                                <div class="tab-pane fade" id="tab-user" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="nama_mahasiswa">
                                                    Nama Lengkap <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nama_mahasiswa" id="nama_mahasiswa"
                                                    class="form-control" placeholder="Masukkan nama lengkap" required
                                                    data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase" for="nim">
                                                    NIM <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="nim" id="nim"
                                                    class="form-control" placeholder="Nomor Induk Mahasiswa" required
                                                    data-error="NIM Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-md-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase"
                                                    for="email_mahasiswa">
                                                    Email (PCR) <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" name="email_mahasiswa" id="email_mahasiswa"
                                                    class="form-control" placeholder="Masukkan email PCR" required
                                                    data-error="Email tidak valid">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="fw-bold text-muted small text-uppercase mb-2"
                                                    for="prodi_id">
                                                    Program Studi <span class="text-danger">*</span>
                                                </label>
                                                <select name="prodi_id" id="prodi_id" class="form-select" required
                                                    data-error="Pilih Program Studi">
                                                    <option value="">-- Pilih Program Studi --</option>
                                                    @foreach (data_get($content, 'prodi_list', []) as $prodi)
                                                        <option value="{{ $prodi->prodi_id }}">
                                                            {{ $prodi->nama_prodi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ACTION BUTTONS --}}
                                    <div class="col-lg-12 mt-4">
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                                onclick="switchTab('tab-requirements')">Sebelumnya</button>
                                            <button type="button" id="btnOpenModal" class="btn-default"
                                                onclick="openConfirmation()">
                                                Ajukan Surat
                                            </button>
                                        </div>

                                        {{-- Loading & Messages --}}
                                        <div class="mt-3 text-center">
                                            <span id="loadingIndicator" class="text-primary d-none fw-bold">
                                                <i class="fa-solid fa-spinner fa-spin me-2"></i> Sedang memproses...
                                            </span>
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
                        <h3 class="wow fadeInUp fs-5">Riwayat Pengajuan Bebas Pustaka</h3>
                    </div>
                    <div class="table-responsive rounded p-4 border border-3">
                        <table class="table table-hover history-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(data_get($content, 'history', []) as $item)
                                    <tr>
                                        <td>{{ tanggal($item->created_at, ' ') }}</td>
                                        <td>{{ $item->nama_mahasiswa }}</td>
                                        <td>{{ $item->nim }}</td>
                                        <td>{{ data_get($item->prodi, 'nama_prodi', '-') }}</td>
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
</section>

{{-- --- CONFIRMATION MODAL --- --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg p-2">
            <div class="modal-header bg-white border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: var(--primary-main)" id="confirmationModalLabel">
                    <i class="fa-solid fa-circle-question me-2"></i>Konfirmasi Pengajuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-secondary fs-6">
                    Apakah data diri Anda sudah benar? <br>
                    Admin akan melakukan verifikasi terhadap pengajuan Anda.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" style="height: 48px"
                    data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default rounded-pill ps-4 pe-5" style="height: 48px"
                    onclick="submitUsulanAjax()">Ya, Ajukan</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('#usulanTabs button[data-bs-target]');
        tabs.forEach((btn, i) => {
            if (i > 0) btn.disabled = true;
        });

        ['check_kp', 'check_pa'].forEach(k => {
            document.querySelectorAll(`input[name="${k}"]`).forEach(r => {
                r.addEventListener('change', () => toggleRepositoryLinkField(k, r.value));
            });
        });
    });

    function toggleRepositoryLinkField(key, val) {
        const box = document.getElementById(`${key}_link_container`);
        const input = document.getElementById(`${key}_link`);
        if (val === '1') {
            box.classList.remove('d-none');
            input.required = true;
        } else {
            box.classList.add('d-none');
            input.required = false;
            input.value = '';
        }
    }

    function validateRequirements() {
        let ok = true;
        ['check_pa', 'check_kp', 'check_hardcopy_kp', 'check_hardcopy_pa', 'check_buku', 'check_modul']
        .forEach(n => {
            const checked = document.querySelector(`input[name="${n}"]:checked`);
            const wrap = document.querySelector(`input[name="${n}"]`).closest('.req-item');
            const err = wrap.querySelector('.error-msg');
            if (!checked || checked.value !== '1') {
                ok = false;
                err.classList.remove('d-none');
            } else {
                err.classList.add('d-none');
            }

            if (n === 'check_kp' && checked?.value === '1') {
                const link = document.getElementById('check_kp_link');
                if (!link.value || !link.checkValidity()) {
                    ok = false;
                    link.reportValidity();
                }
            }

            if (n === 'check_pa' && checked?.value === '1') {
                const link = document.getElementById('check_pa_link');
                if (!link.value || !link.checkValidity()) {
                    ok = false;
                    link.reportValidity();
                }
            }
        });
        return ok;
    }

    function openConfirmation() {
        if (validateTab('tab-user') && validateRequirements()) {
            new bootstrap.Modal('#confirmationModal').show();
        } else {
            document.getElementById('requirements-container')
                .scrollIntoView({
                    behavior: 'smooth'
                });
        }
    }

    function submitUsulanAjax() {
        const form = document.getElementById('formUsulan');
        const btn = document.getElementById('btnOpenModal');
        const load = document.getElementById('loadingIndicator');
        bootstrap.Modal.getInstance(
            document.getElementById('confirmationModal')
        ).hide();
        load.classList.remove('d-none');
        btn.disabled = true;
        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(form)
            })
            .then(r => r.json())
            .then(res => {
                load.classList.add('d-none');
                btn.disabled = false;
                Swal.fire('Berhasil', res.message, 'success');
                form.reset();
                switchTab('tab-attention');
            })
            .catch(() => {
                load.classList.add('d-none');
                btn.disabled = false;
                Swal.fire('Error', 'Gagal submit', 'error');
            });

    }

    function validateTab(id) {
        let valid = true;
        document.querySelectorAll(`#${id} input, #${id} select`)
            .forEach(el => {
                if (!el.checkValidity()) {
                    el.reportValidity();
                    valid = false;
                }
            });
        return valid;
    }

    function switchTab(id) {
        const trigger = document.querySelector(`[data-bs-target="#${id}"]`);
        trigger.disabled = false;
        new bootstrap.Tab(trigger).show();
    }

    function validateAndNext(currentTabId, nextTabId) {
        if (currentTabId === 'tab-requirements') {
            if (validateRequirements()) {
                switchTab(nextTabId);
            } else {
                document.getElementById('requirements-container')
                    .scrollIntoView({
                        behavior: 'smooth'
                    });
            }
        } else {
            if (validateTab(currentTabId)) {
                switchTab(nextTabId);
            }
        }
    }

    function autofillForm() {
        document.querySelector('[name="nama_mahasiswa"]').value = "Mahasiswa Demo";
        document.querySelector('[name="nim"]').value = "2355301999";
        document.querySelector('[name="email_mahasiswa"]').value = "mahasiswa@pcr.ac.id";
        const prodi = document.querySelector('[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;
    }
</script>
