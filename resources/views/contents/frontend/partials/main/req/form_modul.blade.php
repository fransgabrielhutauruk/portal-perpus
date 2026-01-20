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

        /* Radio inline - stack on small screens */
        @media (max-width: 576px) {
            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                border-radius: 0.25rem !important;
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
                            ['id' => 'tab-user', 'label' => '2. Data Dosen', 'active' => false],
                            ['id' => 'tab-book', 'label' => '3. Data Modul', 'active' => false],
                        ],
                        'tabsId' => 'usulanTabs',
                    ])

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
                                @if (data_get($content, 'is_open'))
                                    <div class="d-inline-block text-center border-0 pt-3 px-4 rounded-3 mt-2">
                                        <p class="mb-0" style="color: var(--primary-main)">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Batas Tanggal
                                            Usulan
                                            <strong>{{ data_get($content, 'periode_name') }} </strong> Untuk Periode
                                            Adalah
                                            <strong>{{ \Carbon\Carbon::parse(data_get($content, 'active_periode.tanggal_selesai'))->isoFormat('D MMMM Y') }}</strong>
                                        </p>
                                    </div>
                                @endif

                                <div class="contact-form-btn mt-3">
                                    <button type="button" class="btn-default" onclick="switchTab('tab-user')">
                                        Selanjutnya
                                    </button>
                                </div>

                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill"
                                        onclick="autofillForm()">
                                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Demo Autofill
                                    </button>
                                </div>
                            </div>

                            {{-- === TAB 2: DATA DOSEN === --}}
                            <div class="tab-pane fade" id="tab-user" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="nama_dosen">Nama
                                                Dosen <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_dosen" id="nama_dosen" class="form-control"
                                                placeholder="Masukkan nama" required data-error="Wajib diisi">
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
                                                data-error="Wajib diisi">
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
                                            <label class="fw-bold text-muted small text-uppercase" for="nip">NIP
                                                <span class="text-danger">*</span></label>
                                            <input type="number" name="nip" id="nip" class="form-control"
                                                placeholder="Masukkan NIP" required data-error="NIP Wajib diisi">
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
                                                class="form-control" placeholder="Masukkan nama mata kuliah" required
                                                data-error="Wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="judul_modul">
                                                Judul Modul <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="judul_modul" id="judul_modul"
                                                class="form-control" placeholder="Masukkan judul modul" required
                                                data-error="Wajib diisi">
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
                                                data-error="Wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="tahun_modul">
                                                Tahun Modul <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="tahun_modul" id="tahun_modul"
                                                class="form-control" placeholder="YYYY" required
                                                data-error="Wajib diisi">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase d-block mb-3">Jenis
                                                Modul <span class="text-danger">*</span></label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="praktikum"
                                                    id="typeTeori" value="0" checked>
                                                <label class="form-check-label" for="typeTeori">Teori</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="praktikum"
                                                    id="typePraktikum" value="1">
                                                <label class="form-check-label" for="typePraktikum">Praktikum</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase"
                                                for="jumlah_dibutuhkan">
                                                Jumlah Dibutuhkan
                                            </label>
                                            <input type="number" name="jumlah_dibutuhkan" id="jumlah_dibutuhkan"
                                                class="form-control" placeholder="Masukkan jumlah yang dibutuhkan"
                                                value="0" min="0">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase d-block mb-3">Apakah
                                                Ada Revisi atau Cetak Baru? <span class="text-danger">*</span></label>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="ada_revisi"
                                                    id="revisiTidak" value="0" checked
                                                    onchange="toggleFileUpload()">
                                                <label class="form-check-label" for="revisiTidak">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="ada_revisi"
                                                    id="revisiYa" value="1" onchange="toggleFileUpload()">
                                                <label class="form-check-label" for="revisiYa">Ya</label>
                                            </div>
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
                                    <label class="fw-bold text-muted small text-uppercase" for="deskripsi_kebutuhan">
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

                                    <div class="mt-3 text-center">
                                        <span id="loadingIndicator" class="text-primary d-none fw-bold">
                                            <i class="fa-solid fa-spinner fa-spin me-2"></i> Mengirim data...
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
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
                                        <th>Modul</th>
                                        <th>Mata Kuliah</th>
                                        <th>Dosen</th>
                                        <th>Jenis</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(data_get($content, 'history', []) as $item)
                                        <tr>
                                            <td>
                                                {{ $item->judul_modul }}
                                            </td>
                                            <td>
                                                {{ $item->nama_mata_kuliah }}
                                            </td>
                                            <td>
                                                {{ $item->nama_dosen }}
                                            </td>
                                            <td>
                                                @if ($item->praktikum)
                                                    <span class="badge bg-info text-dark">Praktikum</span>
                                                @else
                                                    <span class="badge bg-light text-dark border">Teori</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($item->status_req == 2)
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
                                            <td colspan="4" class="text-center py-4 text-muted">
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

{{-- --- 4. JAVASCRIPT LOGIC --- --}}

{{-- A. Validator Plugin (Kept as is) --}}
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

{{-- B. Custom Logic --}}
<script>
    $(document).ready(function() {
        $('#formUsulan').validator();
    });

    function openConfirmation() {
        if (validateTab('tab-book')) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }
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
        tbody.querySelector('td[colspan="4"]')?.closest('tr').remove();

        const jenisBadge = data.praktikum == 1 ?
            '<span class="badge bg-info text-dark">Praktikum</span>' :
            '<span class="badge bg-light text-dark border">Teori</span>';

        tbody.insertAdjacentHTML('afterbegin', `
            <tr class="table-success">
                <td>${data.judul_modul}</td>
                <td>${data.nama_mata_kuliah}</td>
                <td>
                    ${data.nama_dosen} <br>
                </td>
                <td>${jenisBadge}</td>
                <td class="text-center"><span class="badge bg-secondary rounded-pill">${data.status}</span></td>
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
