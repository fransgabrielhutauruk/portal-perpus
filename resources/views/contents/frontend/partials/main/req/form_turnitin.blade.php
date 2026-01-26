<style>
    @media (max-width: 768px) {
        .contact-us-form {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .nav-tabs {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            font-size: 0.85rem;
        }

        .nav-tabs .nav-link {
            padding: 0.5rem 0.25rem;
        }

        .tab-content {
            margin-top: 2rem !important;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .btn-default,
        .btn-outline-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
        }

        .d-inline-block {
            display: block !important;
            width: 100%;
        }

        h5 {
            font-size: 1rem;
        }

        h3 {
            font-size: 1.25rem;
        }

        label {
            font-size: 0.875rem;
        }

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
                                    <p class="mb-0">Setelah Selesai Mengisi Formulir Ini, Petugas Akan
                                        Mengonfirmasikan Melalui Email, Pastikan Email yang Anda Masukkan Benar.</p>
                                </div>

                                <div class="contact-form-btn mt-3">
                                    <button type="button" class="btn-default" onclick="switchTab('tab-dosen')">
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
                            <div class="tab-pane fade" id="tab-dosen" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-bold text-muted small text-uppercase" for="nama_dosen">
                                                Nama Dosen <span class="text-danger">*</span>
                                            </label>
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
                                            <input type="text" name="nip" id="nip" class="form-control"
                                                placeholder="Masukkan NIP" required data-error="NIP Wajib diisi">
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
                                                    id="typeSkripsi" value="Karya Ilmiah" required>
                                                <label class="form-check-label" for="typeSkripsi">
                                                    Karya Ilmiah
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="jenis_dokumen"
                                                    id="typeArtikel" value="Proyek Akhir" required>
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
                                                required data-error="Wajib diisi">
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
                                                placeholder="Jelaskan alasan atau tujuan pengajuan cek turnitin ini..." required data-error="Wajib diisi"></textarea>
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
                                            <div id="loadingIndicator" class="d-none me-2">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                            <button type="button" id="btnOpenModal" class="btn-default"
                                                onclick="openConfirmation()">
                                                <i class="fa-solid fa-paper-plane me-2"></i>Kirim Pengajuan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                    <i class="fa-solid fa-circle-question me-2"></i>Konfirmasi Pengajuan Turnitin
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

{{-- === JAVASCRIPT === --}}
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
                        c.data("bs.validator.errors", b), b.length ? g.showErrors(c) : g.clearErrors(c), e && b
                            .toString() === e.toString() || (f = b.length ? a.Event("invalid.bs.validator", {
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
                            "data-pattern-error") : a.stepMismatch ? c.attr("data-step-error") : a.rangeOverflow ?
                        c.attr("data-max-error") : a.rangeUnderflow ? c.attr("data-min-error") : a
                        .tooLong ? c.attr("data-maxlength-error") : void 0
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
                    .feedback.error) && e.removeClass("has-success"))
            }, d.prototype.clearErrors = function(a) {
                var c = a.closest(".form-group"),
                    d = c.find(".help-block.with-errors"),
                    e = c.find(".form-control-feedback");
                d.html(d.data("bs.validator.originalContent")), c.removeClass("has-error has-danger has-success"), c
                    .hasClass("has-feedback") && e.removeClass(this.options.feedback.error) && e.removeClass(
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
                        a(this).closest(".form-group").removeClass("has-error has-danger has-success")
                    }), this.$element.find(".help-block.with-errors").each(function() {
                        var b = a(this),
                            c = b.data("bs.validator.originalContent");
                        b.data("bs.validator.originalContent", !1), b.html(c || "")
                    }), this.$btn.removeClass("disabled"), this.$element.find(".has-error, .has-danger, .has-success")
                    .removeClass("has-error has-danger has-success"), this
            }, d.prototype.destroy = function() {
                return this.reset(), this.$element.removeAttr("novalidate").removeData("bs.validator").off(
                        ".bs.validator"), this.$inputs.off(".bs.validator"), this.options = null, this
                    .validators = null, this.$element = null, this.$btn = null, this
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
        $('#formTurnitin').validator();
    });

    function openConfirmation() {
        if (validateTab('tab-dokumen')) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }
    }

    function submitTurnitinAjax() {
        const form = document.getElementById('formTurnitin');
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

    function autofillForm() {
        document.querySelector('input[name="nama_dosen"]').value = "Dr. Ahmad Fauzi, M.Kom";
        document.querySelector('input[name="inisial_dosen"]').value = "AF";
        document.querySelector('input[name="email_dosen"]').value = "ahmad.fauzi@pcr.ac.id";
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
