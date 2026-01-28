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

{{-- --- JAVASCRIPT LOGIC --- --}}
<script>
    /*! Validator v0.11.9 by @1000hz */ + function(a) {
        "use strict";

        function b(b) {
            return b.is('[type="checkbox"]') ? b.prop("checked") : b.is('[type="radio"]') ? !!a('[name="' + b.attr(
                "name") + '"]:checked').length : b.is("select[multiple]") ? (b.val() || []).length : b.val()
        }

        function c(b) {
            return this.each(function() {
                var c = a(this),
                    e = a.extend({}, d.DEFAULTS, c.data(), "object" == typeof b && b),
                    f = c.data("bs.validator");
                (f || "destroy" != b) && (f || c.data("bs.validator", f = new d(this, e)), "string" ==
                    typeof b && f[b]())
            })
        }
        var d = function(c, e) {
            this.options = e, this.validators = a.extend({}, d.VALIDATORS, e.custom), this.$element = a(c), this
                .$btn = a('button[type="submit"], input[type="submit"]').filter('[form="' + this.$element.attr(
                    "id") + '"]').add(this.$element.find('input[type="submit"], button[type="submit"]')), this
                .update(), this.$element.on("input.bs.validator change.bs.validator focusout.bs.validator", a.proxy(
                    this.onInput, this)), this.$element.on("submit.bs.validator", a.proxy(this.onSubmit, this)),
                this.$element.on("reset.bs.validator", a.proxy(this.reset, this)), this.$element.find(
                    "[data-match]").each(function() {
                    var c = a(this),
                        d = c.attr("data-match");
                    a(d).on("input.bs.validator", function() {
                        b(c) && c.trigger("input.bs.validator")
                    })
                }), this.$inputs.filter(function() {
                    return b(a(this)) && !a(this).closest(".has-error").length
                }).trigger("focusout"), this.$element.attr("novalidate", !0)
        };
        d.VERSION = "0.11.9", d.INPUT_SELECTOR = ':input:not([type="hidden"], [type="submit"], [type="reset"], button)',
            d.FOCUS_OFFSET = 20, d.DEFAULTS = {
                delay: 500,
                html: !1,
                disable: !0,
                focus: !0,
                custom: {},
                errors: {
                    match: "Does not match",
                    minlength: "Not long enough"
                },
                feedback: {
                    success: "glyphicon-ok",
                    error: "glyphicon-remove"
                }
            }, d.VALIDATORS = {
                "native": function(a) {
                    var b = a[0];
                    return b.checkValidity ? !b.checkValidity() && !b.validity.valid && (b.validationMessage ||
                        "error!") : void 0
                },
                match: function(b) {
                    var c = b.attr("data-match");
                    return b.val() !== a(c).val() && d.DEFAULTS.errors.match
                },
                minlength: function(a) {
                    var b = a.attr("data-minlength");
                    return a.val().length < b && d.DEFAULTS.errors.minlength
                }
            }, d.prototype.update = function() {
                var b = this;
                return this.$inputs = this.$element.find(d.INPUT_SELECTOR).add(this.$element.find(
                    '[data-validate="true"]')).not(this.$element.find('[data-validate="false"]').each(function() {
                    b.clearErrors(a(this))
                })), this.toggleSubmit(), this
            }, d.prototype.onInput = function(b) {
                var c = this,
                    d = a(b.target),
                    e = "focusout" !== b.type;
                this.$inputs.is(d) && this.validateInput(d, e).done(function() {
                    c.toggleSubmit()
                })
            }, d.prototype.validateInput = function(c, d) {
                var e = (b(c), c.data("bs.validator.errors"));
                c.is('[type="radio"]') && (c = this.$element.find('input[name="' + c.attr("name") + '"]'));
                var f = a.Event("validate.bs.validator", {
                    relatedTarget: c[0]
                });
                if (this.$element.trigger(f), !f.isDefaultPrevented()) {
                    var g = this;
                    return this.runValidators(c).done(function(b) {
                        c.data("bs.validator.errors", b), b.length ? d ? g.defer(c, g.showErrors) : g
                            .showErrors(c) : g.clearErrors(c), e && b.toString() === e.toString() || (f = b
                                .length ? a.Event("invalid.bs.validator", {
                                    relatedTarget: c[0],
                                    detail: b
                                }) : a.Event("valid.bs.validator", {
                                    relatedTarget: c[0],
                                    detail: e
                                }), g.$element.trigger(f)), g.toggleSubmit(), g.$element.trigger(a.Event(
                                "validated.bs.validator", {
                                    relatedTarget: c[0]
                                }))
                    })
                }
            }, d.prototype.runValidators = function(c) {
                function d(a) {
                    return c.attr("data-" + a + "-error")
                }

                function e() {
                    var a = c[0].validity;
                    return a.typeMismatch ? c.attr("data-type-error") : a.patternMismatch ? c.attr(
                            "data-pattern-error") : a.stepMismatch ? c.attr("data-step-error") : a.rangeOverflow ? c
                        .attr(
                            "data-max-error") : a.rangeUnderflow ? c.attr("data-min-error") : a.valueMissing ? c.attr(
                            "data-required-error") : null
                }

                function f() {
                    return c.attr("data-error")
                }

                function g(a) {
                    return d(a) || e() || f()
                }
                var h = [],
                    i = a.Deferred();
                return c.data("bs.validator.deferred") && c.data("bs.validator.deferred").reject(), c.data(
                    "bs.validator.deferred", i), a.each(this.validators, a.proxy(function(a, d) {
                    var e = null;
                    !b(c) && !c.attr("required") || void 0 === c.attr("data-" + a) && "native" != a || !(e =
                        d.call(this, c)) || (e = g(a) || e, !~h.indexOf(e) && h.push(e))
                }, this)), !h.length && b(c) && c.attr("data-remote") ? this.defer(c, function() {
                    var d = {};
                    d[c.attr("name")] = b(c), a.get(c.attr("data-remote"), d).fail(function(a, b, c) {
                        h.push(g("remote") || c)
                    }).always(function() {
                        i.resolve(h)
                    })
                }) : i.resolve(h), i.promise()
            }, d.prototype.validate = function() {
                var b = this;
                return a.when(this.$inputs.map(function() {
                    return b.validateInput(a(this), !1)
                })).then(function() {
                    b.toggleSubmit(), b.focusError()
                }), this
            }, d.prototype.focusError = function() {
                if (this.options.focus) {
                    var b = this.$element.find(".has-error:first :input");
                    0 !== b.length && (a("html, body").animate({
                        scrollTop: b.offset().top - d.FOCUS_OFFSET
                    }, 250), b.focus())
                }
            }, d.prototype.showErrors = function(b) {
                var c = this.options.html ? "html" : "text",
                    d = b.data("bs.validator.errors"),
                    e = b.closest(".form-group"),
                    f = e.find(".help-block.with-errors"),
                    g = e.find(".form-control-feedback");
                d.length && (d = a("<ul/>").addClass("list-unstyled").append(a.map(d, function(b) {
                    return a("<li/>")[c](b)
                })), void 0 === f.data("bs.validator.originalContent") && f.data("bs.validator.originalContent",
                    f.html()), f.empty().append(d), e.addClass("has-error has-danger"), e.hasClass(
                    "has-feedback") && g.removeClass(this.options.feedback.success) && g.addClass(this.options
                    .feedback.error) && e.removeClass(this.options.feedback.success) && e.removeClass(
                    "has-success"))
            }, d.prototype.clearErrors = function(a) {
                var c = a.closest(".form-group"),
                    d = c.find(".help-block.with-errors"),
                    e = c.find(".form-control-feedback");
                d.html(d.data("bs.validator.originalContent")), c.removeClass("has-error has-danger has-success"), c
                    .hasClass("has-feedback") && e.removeClass(this.options.feedback.error) && e.removeClass(this
                        .options.feedback.success) && b(a) && e.addClass(this.options.feedback.success) && c.addClass(
                        "has-success")
            }, d.prototype.hasErrors = function() {
                function b() {
                    return !!(a(this).data("bs.validator.errors") || []).length
                }
                return !!this.$inputs.filter(b).length
            }, d.prototype.isIncomplete = function() {
                function c() {
                    var c = b(a(this));
                    return !("string" == typeof c ? a.trim(c) : c)
                }
                return !!this.$inputs.filter("[required]").filter(c).length
            }, d.prototype.onSubmit = function(a) {
                this.validate(), (this.isIncomplete() || this.hasErrors()) && a.preventDefault()
            }, d.prototype.toggleSubmit = function() {
                this.options.disable && this.$btn.toggleClass("disabled", this.isIncomplete() || this.hasErrors())
            }, d.prototype.defer = function(b, c) {
                return c = a.proxy(c, this, b), this.options.delay ? (window.clearTimeout(b.data(
                    "bs.validator.timeout")), void b.data("bs.validator.timeout", window.setTimeout(c, this
                    .options.delay))) : c()
            }, d.prototype.reset = function() {
                return this.$element.find(".form-control-feedback").removeClass(this.options.feedback.error)
                    .removeClass(this.options.feedback.success), this.$inputs.removeData(["bs.validator.errors",
                        "bs.validator.deferred"
                    ]).each(function() {
                        var b = a(this),
                            c = b.data("bs.validator.timeout");
                        window.clearTimeout(c) && b.removeData("bs.validator.timeout")
                    }), this.$element.find(".help-block.with-errors").each(function() {
                        var b = a(this),
                            c = b.data("bs.validator.originalContent");
                        b.removeData("bs.validator.originalContent").html(c)
                    }), this.$btn.removeClass("disabled"), this.$element.find(".has-error, .has-danger, .has-success")
                    .removeClass("has-error has-danger has-success"), this
            }, d.prototype.destroy = function() {
                return this.reset(), this.$element.removeAttr("novalidate").removeData("bs.validator").off(
                        ".bs.validator"), this.$inputs.off(".bs.validator"), this.options = null, this.validators =
                    null, this.$element = null, this.$btn = null, this.$inputs = null, this
            };
        var e = a.fn.validator;
        a.fn.validator = c, a.fn.validator.Constructor = d, a.fn.validator.noConflict = function() {
            return a.fn.validator = e, this
        }, a(window).on("load", function() {
            a('form[data-toggle="validator"]').each(function() {
                var b = a(this);
                c.call(b, b.data())
            })
        })
    }(jQuery);
</script>

<script>
    $(document).ready(function() {
        // Initialize Validator
        $('#formUsulan').validator();

        // Enable all tab navigation
        document.querySelectorAll('#usulanTabs button[data-bs-target]').forEach(button => {
            button.disabled = false;
            button.style.cursor = 'pointer';
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-bs-target').substring(1); // Remove #
                switchTab(targetId);
            });
        });
    });

    function validateRequirements() {
        let isValid = true;
        const requirements = ['check_pa', 'check_kp', 'check_hardcopy_kp', 'check_hardcopy_pa',
            'check_buku', 'check_modul'
        ];

        requirements.forEach(name => {
            const val = $(`input[name="${name}"]:checked`).val();
            const container = $(`input[name="${name}"]`).closest('.req-item');
            const errorMsg = container.find('.error-msg');

            // Cek jika belum dipilih (undefined) atau dipilih "0" (Tidak)
            if (val !== '1') {
                isValid = false;
                errorMsg.removeClass('d-none'); // Tampilkan pesan error per item
            } else {
                errorMsg.addClass('d-none');
            }
        });

        const globalError = $('#globalError');
        if (!isValid) {
            globalError.removeClass('d-none');
        } else {
            globalError.addClass('d-none');
        }

        return isValid;
    }

    // --- 1. Modal Logic ---
    function openConfirmation() {
        // 1. Validasi Input Standar (Nama, NIM, dll)
        const isInfoValid = validateTab('tab-user');

        // 2. Validasi Syarat Khusus (Semua harus "1")
        const isRequirementsValid = validateRequirements();

        if (isInfoValid && isRequirementsValid) {
            // Jika semua valid, tampilkan modal konfirmasi
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();
        } else if (!isRequirementsValid) {
            // Jika syarat tidak valid, scroll ke bagian syarat
            $('html, body').animate({
                scrollTop: $("#requirements-container").offset().top - 100
            }, 500);
        }
    }

    // --- 2. AJAX Submission ---
    function submitUsulanAjax() {
        const formUsulan = document.getElementById('formUsulan');
        const btnOpenModal = document.getElementById('btnOpenModal');
        const loadingIndicator = document.getElementById('loadingIndicator');

        // Hide Modal
        var modalEl = document.getElementById('confirmationModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        // UI Loading State
        loadingIndicator.classList.remove('d-none');
        btnOpenModal.disabled = true;

        // Prepare Data
        const formData = new FormData(formUsulan);
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        fetch(formUsulan.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                loadingIndicator.classList.add('d-none');
                btnOpenModal.disabled = false;

                if (status === 200 || status === 201) {
                    // SUCCESS
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: body.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745',
                        allowOutsideClick: false
                    }).then(() => {
                        formUsulan.reset();
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
                console.error('Error:', error);
                loadingIndicator.classList.add('d-none');
                btnOpenModal.disabled = false;

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan jaringan.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            });
    }

    // --- NEW FUNCTION: Add Row to Table (Updated for Bebas Pustaka) ---
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
                <td>${data.nama_mahasiswa}</td>
                <td>${data.nim}</td>
                <td>${data.prodi_nama}</td>
                <td class="text-center">${statusBadges[data.status_req] || statusBadges.default}</td>
            </tr>`);

        setTimeout(() => tbody.querySelector('tr.table-success')?.classList.remove('table-success'), 2000);
    }

    // --- 3. Validation Helpers ---
    function validateTab(tabId) {
        var $currentTab = $('#' + tabId);
        // Kita hanya validasi input standar di sini (text, email, number, select)
        var $inputs = $currentTab.find('input[type="text"], input[type="number"], input[type="email"], select');
        var isValid = true;

        $inputs.each(function() {
            var $el = $(this);
            // Cek validitas bawaan HTML5
            if (!this.checkValidity()) isValid = false;
            // Trigger validator plugin
            $el.trigger('input');
            // Cek class error dari validator plugin
            if ($el.closest('.form-group').hasClass('has-error')) isValid = false;
        });

        return isValid;
    }

    function validateAndNext(currentTabId, nextTabId) {
        // Special handling for requirements tab
        if (currentTabId === 'tab-requirements') {
            const isRequirementsValid = validateRequirements();
            if (isRequirementsValid) {
                switchTab(nextTabId);
            } else {
                // Scroll to requirements container
                $('html, body').animate({
                    scrollTop: $("#requirements-container").offset().top - 100
                }, 500);
            }
        } else if (validateTab(currentTabId)) {
            switchTab(nextTabId);
        }
    }

    // --- 4. Wizard Helpers ---
    function switchTab(targetId) {
        const triggerEl = document.querySelector(`button[data-bs-target="#${targetId}"]`);
        const tab = new bootstrap.Tab(triggerEl);
        triggerEl.disabled = false;
        tab.show();
        document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
        triggerEl.classList.add('active');
    }

    function autofillForm() {

        document.querySelector('input[name="nama_mahasiswa"]').value = "Mahasiswa Calon Wisudawan";
        document.querySelector('input[name="email_mahasiswa"]').value = "mahasiswa@pcr.ac.id";
        document.querySelector('input[name="nim"]').value = "2355301999";

        const prodi = document.querySelector('select[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;

        $('#formUsulan').validator('update');
        $('#formUsulan').find('.form-control').trigger('input');
    }
</script>
