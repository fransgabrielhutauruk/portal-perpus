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
                            <input type="hidden" name="verification_token" id="verification_token" value="">
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
                                        <p class="mb-0">Setelah selesai mengisi formulir ini, petugas akan
                                            mengonfirmasikan melalui Email, pastikan Email yang Anda masukkan benar.</p>
                                    </div>

                                    <div class="mt-3">
                                        <p class="mb-0 text-muted">Sebelum melanjutkan, lakukan verifikasi menggunakan
                                            email kampus.</p>
                                    </div>

                                    @if (data_get($content, 'is_open'))
                                        <div class="contact-form-btn mt-3">
                                            <button type="button" id="btnToStep2" class="btn-default"
                                                onclick="handleGoogleVerification()">
                                                Verifikasi melalui Google
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
                                                        <option value="{{ $prodi->prodi_id }}"
                                                            data-alias="{{ $prodi->alias_prodi }}">
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
                                        <td class="align-middle">{{ tanggal($item->created_at, ' ') }}</td>
                                        <td class="align-middle">{{ $item->nama_mahasiswa }}</td>
                                        <td class="align-middle">{{ $item->nim }}</td>
                                        <td class="align-middle">{{ data_get($item->prodi, 'nama_prodi', '-') }}</td>
                                        <td class="text-center align-middle">
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

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
@endif

<script>
    const verifyIdentityUrl = "{{ route('frontend.req.bebas-pustaka.verify') }}";
    const googleLoginUrl = "{{ route('login.google.verify', ['provider' => 'google']) }}";
    const googleLoginRedirect = "{{ url()->current() }}";
    const isAuthenticated = @json(auth()->check());
    const hasVerifiedEmail = @json(session()->has('verified_google_email'));

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

        if (hasVerifiedEmail) {
            verifyIdentity();
        }
    });

    function handleGoogleVerification() {
        if (!isAuthenticated && !hasVerifiedEmail) {
            const targetUrl = `${googleLoginUrl}?redirect=${encodeURIComponent(googleLoginRedirect)}`;
            window.location.href = targetUrl;
            return;
        }

        verifyIdentity();
    }

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
        const disabledFields = form.querySelectorAll(':disabled');

        disabledFields.forEach(field => field.disabled = false);

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
                disabledFields.forEach(field => field.disabled = true);
                load.classList.add('d-none');
                btn.disabled = false;
                Swal.fire('Berhasil', res.message, 'success');
                if (res.new_data) addHistoryRow(res.new_data);
                form.reset();
                switchTab('tab-attention');
            })
            .catch(() => {
                disabledFields.forEach(field => field.disabled = true);
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

    function addHistoryRow(data) {
        const tbody = document.querySelector('.history-table tbody');
        tbody.querySelector('td[colspan="5"]')?.closest('tr')?.remove();

        const statusBadges = {
            0: '<span class="badge bg-warning text-dark rounded-pill">Menunggu</span>',
            1: '<span class="badge bg-success rounded-pill">Disetujui</span>',
            default: '<span class="badge bg-danger rounded-pill">Ditolak</span>'
        };

        const statusCell = data.status_req == -1 && data.catatan_admin
            ? `${statusBadges[data.status_req] || statusBadges.default}<small class="d-block text-muted mt-1" style="font-size: 0.75rem;">[Catatan: ${data.catatan_admin}]</small>`
            : (statusBadges[data.status_req] || statusBadges.default);

        tbody.insertAdjacentHTML('afterbegin', `
            <tr class="table-success">
                <td class="align-middle">${data.date_fmt || '-'}</td>
                <td class="align-middle">${data.nama_mahasiswa || '-'}</td>
                <td class="align-middle">${data.nim || '-'}</td>
                <td class="align-middle">${data.prodi_nama || data.nama_prodi || '-'}</td>
                <td class="text-center align-middle">${statusCell}</td>
            </tr>`);

        setTimeout(() => tbody.querySelector('tr.table-success')?.classList.remove('table-success'), 2000);
    }

    function switchTab(id) {
        const trigger = document.querySelector(`[data-bs-target="#${id}"]`);
        trigger.disabled = false;
        new bootstrap.Tab(trigger).show();
    }

    function setFieldReadonly(fieldId, readonly) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        field.disabled = readonly;
    }

    function setProdiFromAlias(prodiAlias) {
        const select = document.getElementById('prodi_id');
        if (!select) return;

        const alias = (prodiAlias || '').toString().trim().toLowerCase();
        const options = Array.from(select.options);
        const match = options.find(option => (option.dataset.alias || '').toLowerCase() === alias);

        if (match) {
            select.value = match.value;
            select.disabled = true;
        } else {
            select.disabled = false;
        }
    }

    function applyVerifiedIdentity(data) {
        const namaField = document.getElementById('nama_mahasiswa');
        const emailField = document.getElementById('email_mahasiswa');
        const nimField = document.getElementById('nim');
        const tokenField = document.getElementById('verification_token');

        if (namaField) namaField.value = data.nama || '';
        if (emailField) emailField.value = data.email || '';
        if (nimField) {
            nimField.value = data.nim || '';
            nimField.readOnly = true;
        }
        if (tokenField) tokenField.value = data.verification_token || '';

        setFieldReadonly('nama_mahasiswa', true);
        setFieldReadonly('email_mahasiswa', true);
        setFieldReadonly('nim', true);
        setProdiFromAlias(data.prodi || '');

        switchTab('tab-requirements');
    }

    function verifyIdentity() {
        Swal.fire({
            html: 'Memverifikasi akun...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(verifyIdentityUrl, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Server mengembalikan response tidak valid');
                }

                const body = await res.json();
                return {
                    status: res.status,
                    body
                };
            })
            .then(({
                status,
                body
            }) => {
                if (status === 401) {
                    const targetUrl = body.login_url ||
                        `${googleLoginUrl}?redirect=${encodeURIComponent(googleLoginRedirect)}`;
                    window.location.href = targetUrl;
                    return;
                }

                if (status >= 400) {
                    throw new Error(body.message || 'Verifikasi gagal.');
                }

                if (body.status !== 'success' || !body.data) {
                    throw new Error(body.message || 'Verifikasi gagal.');
                }

                Swal.close();
                applyVerifiedIdentity(body.data);
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan jaringan.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            });
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
