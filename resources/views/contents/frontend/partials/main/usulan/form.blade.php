{{-- --- 2. MAIN CONTENT SECTION --- --}}
<section class="page-contact-us contact-section">
    <div class="container">
        
        {{-- Section Title --}}
        <div class="row section-row align-items-center justify-content-center">
            <div class="col-lg-8 col-md-10 text-center">
                <div class="section-title">
                    <h3 class="wow fadeInUp">{{ data_get($content, 'subtitle') }}</h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">{{ data_get($content, 'title') }}</h2>
                    <p class="wow fadeInUp mt-3" data-wow-delay="0.3s">{{ data_get($content, 'description') }}</p>
                </div>
                
                {{-- Date Badge --}}
                <div class="wow fadeInUp mt-3" data-wow-delay="0.4s">
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-normal">
                        <i class="fa-solid fa-calendar-day me-2 text-primary"></i> 
                        Hari ini: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                
                {{-- LOGIC: Check if Period is Open --}}
                @if(data_get($content, 'is_open'))

                    <div class="contact-us-form divider-dark-lg wow fadeInUp" data-wow-delay="0.4s">
                        
                        {{-- Tab Headers --}}
                        <ul class="nav nav-tabs nav-fill" id="usulanTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-attention-btn" data-bs-target="#tab-attention" type="button" role="tab" disabled>
                                    1. Perhatian
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-user-btn" data-bs-target="#tab-user" type="button" role="tab" disabled>
                                    2. Data Pengusul
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-book-btn" data-bs-target="#tab-book" type="button" role="tab" disabled>
                                    3. Data Buku
                                </button>
                            </li>
                        </ul>

                        {{-- FORM START --}}
                        <form id="formUsulan" action="{{ data_get($content, 'form.action_url') }}" method="POST" data-toggle="validator">
                            @csrf
                            
                            <div class="tab-content" id="usulanTabsContent">
                                
                                {{-- === TAB 1: ATTENTION === --}}
                                <div class="tab-pane fade show active text-center" id="tab-attention" role="tabpanel">
                                    
                                    {{-- Active Period Info --}}
                                    <div class="alert alert-info d-inline-block text-start mb-4 border-0 bg-light p-4 rounded-3">
                                        <h5 class="text-dark mb-3"><i class="fa-solid fa-circle-info me-2 text-primary"></i> Informasi Periode</h5>
                                        <p class="mb-1">Periode: <strong>{{ data_get($content, 'periode_name') }}</strong></p>
                                        <p class="mb-0">Ditutup pada: <strong>{{ \Carbon\Carbon::parse(data_get($content, 'active_periode.tanggal_selesai'))->isoFormat('D MMMM Y') }}</strong></p>
                                    </div>

                                    <div class="mb-4">
                                        <p>Pastikan Anda telah melakukan pengecekan ketersediaan buku melalui <a href="{{ data_get($content, 'opac_url', '#') }}" target="_blank" class="fw-bold text-primary text-decoration-underline">OPAC</a> sebelum melanjutkan.</p>
                                    </div>

                                    {{-- Demo Button --}}
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill" onclick="autofillForm()">
                                            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Demo Autofill
                                        </button>
                                    </div>

                                    {{-- Agreement Checkbox --}}
                                    <div class="form-group d-inline-block text-start border p-3 rounded mb-4 w-100" style="max-width: 500px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="agreementCheck" onchange="toggleNextButton()">
                                            <label class="form-check-label fw-bold" for="agreementCheck">
                                                Saya sudah memeriksa OPAC dan buku belum tersedia.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="contact-form-btn">
                                        <button type="button" id="btnToStep2" class="btn-default" disabled onclick="switchTab('tab-user')">
                                            Selanjutnya
                                        </button>
                                    </div>
                                </div>

                                {{-- === TAB 2: DATA PENGUSUL === --}}
                                <div class="tab-pane fade" id="tab-user" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Nama Lengkap</label>
                                                <input type="text" name="nama_req" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Email (PCR)</label>
                                                <input type="email" name="email_req" class="form-control" required data-error="Email tidak valid">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Tipe Identitas</label>
                                                <select class="form-select" id="identity_type" onchange="toggleIdentity(this.value)">
                                                    <option value="nim">Mahasiswa (NIM)</option>
                                                    <option value="nip">Dosen/Staff (NIP)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Nomor Identitas</label>
                                                <input type="number" name="nim" id="input_nim" class="form-control" placeholder="NIM" required data-error="NIM Wajib diisi">
                                                <input type="number" name="nip" id="input_nip" class="form-control" placeholder="NIP" style="display:none;" data-error="NIP Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Program Studi</label>
                                        <select name="prodi_id" class="form-select" required data-error="Pilih Program Studi">
                                            <option value="">-- Pilih Program Studi --</option>
                                            @foreach(data_get($content, 'prodi_list', []) as $prodi)
                                                <option value="{{ $prodi->prodi_id }}">{{ $prodi->nama_prodi }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="switchTab('tab-attention')">Sebelumnya</button>
                                        <button type="button" class="btn-default" onclick="validateAndNext('tab-user', 'tab-book')">Selanjutnya</button>
                                    </div>
                                </div>

                                {{-- === TAB 3: DATA BUKU === --}}
                                <div class="tab-pane fade" id="tab-book" role="tabpanel">
                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Judul Buku</label>
                                        <input type="text" name="judul_buku" class="form-control" required data-error="Wajib diisi">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Penulis</label>
                                                <input type="text" name="penulis_buku" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Tahun Terbit</label>
                                                <input type="number" name="tahun_terbit" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Penerbit</label>
                                                <input type="text" name="penerbit_buku" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Estimasi Harga (Rp)</label>
                                                <input type="number" name="estimasi_harga" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Link Pembelian</label>
                                        <input type="url" name="link_pembelian" class="form-control" placeholder="https://...">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Alasan Usulan</label>
                                        <textarea name="alasan_usulan" class="form-control" rows="3" required data-error="Wajib diisi"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    {{-- ACTION BUTTONS --}}
                                    <div class="col-lg-12 mt-4">
                                        <div class="contact-form-btn d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="switchTab('tab-user')">Sebelumnya</button>
                                            
                                            <div>
                                                {{-- Triggers Modal, NOT Submit --}}
                                                <button type="button" id="btnOpenModal" class="btn-default" onclick="openConfirmation()">
                                                    Kirim Usulan
                                                </button>
                                            </div>
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
                    </div>

                    {{-- --- HISTORY TABLE --- --}}
                    <div class="contact-us-form divider-dark-lg mt-5 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="contact-us-title mb-4">
                            <h3>Riwayat Usulan Terbaru</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Pengusul</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(data_get($content, 'history', []) as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->judul_buku }}</div>
                                            <small class="text-muted">{{ $item->penulis_buku }}</small>
                                        </td>
                                        <td>{{ $item->nama_req }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                        <td class="text-center">
                                            @if($item->status_req == 0)
                                                <span class="badge bg-warning text-dark rounded-pill">Menunggu</span>
                                            @elseif($item->status_req == 1)
                                                <span class="badge bg-success rounded-pill">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                @else
                    {{-- --- CLOSED STATE --- --}}
                    <div class="contact-us-form divider-dark-lg text-center p-5 wow fadeInUp">
                        <div class="mb-4 text-secondary opacity-50">
                            <i class="fa-solid fa-calendar-xmark fa-4x"></i>
                        </div>
                        <h3>Pengusulan Ditutup</h3>
                        <p class="mb-4">Saat ini tidak ada periode pengusulan buku yang aktif.</p>
                        <a href="{{ route('frontend.home') }}" class="btn-default">Kembali ke Beranda</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

{{-- --- 3. CONFIRMATION MODAL --- --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="confirmationModalLabel">
                    <i class="fa-solid fa-circle-question me-2"></i> Konfirmasi Pengusulan
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
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default rounded-pill px-4" onclick="submitUsulanAjax()">
                    <i class="fa-solid fa-paper-plane me-2"></i> Ya, Kirim
                </button>
            </div>
        </div>
    </div>
</div>

{{-- --- 4. JAVASCRIPT LOGIC --- --}}

{{-- A. Validator Plugin (Minified) --}}
<script>
/*! Validator v0.11.9 by @1000hz */
+function(a){"use strict";function b(b){return b.is('[type="checkbox"]')?b.prop("checked"):b.is('[type="radio"]')?!!a('[name="'+b.attr("name")+'"]:checked').length:b.is("select[multiple]")?(b.val()||[]).length:b.val()}function c(b){return this.each(function(){var c=a(this),e=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b),f=c.data("bs.validator");(f||"destroy"!=b)&&(f||c.data("bs.validator",f=new d(this,e)),"string"==typeof b&&f[b]())})}var d=function(c,e){this.options=e,this.validators=a.extend({},d.VALIDATORS,e.custom),this.$element=a(c),this.$btn=a('button[type="submit"], input[type="submit"]').filter('[form="'+this.$element.attr("id")+'"]').add(this.$element.find('input[type="submit"], button[type="submit"]')),this.update(),this.$element.on("input.bs.validator change.bs.validator focusout.bs.validator",a.proxy(this.onInput,this)),this.$element.on("submit.bs.validator",a.proxy(this.onSubmit,this)),this.$element.on("reset.bs.validator",a.proxy(this.reset,this)),this.$element.find("[data-match]").each(function(){var c=a(this),d=c.attr("data-match");a(d).on("input.bs.validator",function(){b(c)&&c.trigger("input.bs.validator")})}),this.$inputs.filter(function(){return b(a(this))&&!a(this).closest(".has-error").length}).trigger("focusout"),this.$element.attr("novalidate",!0)};d.VERSION="0.11.9",d.INPUT_SELECTOR=':input:not([type="hidden"], [type="submit"], [type="reset"], button)',d.FOCUS_OFFSET=20,d.DEFAULTS={delay:500,html:!1,disable:!0,focus:!0,custom:{},errors:{match:"Does not match",minlength:"Not long enough"},feedback:{success:"glyphicon-ok",error:"glyphicon-remove"}},d.VALIDATORS={"native":function(a){var b=a[0];return b.checkValidity?!b.checkValidity()&&!b.validity.valid&&(b.validationMessage||"error!"):void 0},match:function(b){var c=b.attr("data-match");return b.val()!==a(c).val()&&d.DEFAULTS.errors.match},minlength:function(a){var b=a.attr("data-minlength");return a.val().length<b&&d.DEFAULTS.errors.minlength}},d.prototype.update=function(){var b=this;return this.$inputs=this.$element.find(d.INPUT_SELECTOR).add(this.$element.find('[data-validate="true"]')).not(this.$element.find('[data-validate="false"]').each(function(){b.clearErrors(a(this))})),this.toggleSubmit(),this},d.prototype.onInput=function(b){var c=this,d=a(b.target),e="focusout"!==b.type;this.$inputs.is(d)&&this.validateInput(d,e).done(function(){c.toggleSubmit()})},d.prototype.validateInput=function(c,d){var e=(b(c),c.data("bs.validator.errors"));c.is('[type="radio"]')&&(c=this.$element.find('input[name="'+c.attr("name")+'"]'));var f=a.Event("validate.bs.validator",{relatedTarget:c[0]});if(this.$element.trigger(f),!f.isDefaultPrevented()){var g=this;return this.runValidators(c).done(function(b){c.data("bs.validator.errors",b),b.length?d?g.defer(c,g.showErrors):g.showErrors(c):g.clearErrors(c),e&&b.toString()===e.toString()||(f=b.length?a.Event("invalid.bs.validator",{relatedTarget:c[0],detail:b}):a.Event("valid.bs.validator",{relatedTarget:c[0],detail:e}),g.$element.trigger(f)),g.toggleSubmit(),g.$element.trigger(a.Event("validated.bs.validator",{relatedTarget:c[0]}))})}},d.prototype.runValidators=function(c){function d(a){return c.attr("data-"+a+"-error")}function e(){var a=c[0].validity;return a.typeMismatch?c.attr("data-type-error"):a.patternMismatch?c.attr("data-pattern-error"):a.stepMismatch?c.attr("data-step-error"):a.rangeOverflow?c.attr("data-max-error"):a.rangeUnderflow?c.attr("data-min-error"):a.valueMissing?c.attr("data-required-error"):null}function f(){return c.attr("data-error")}function g(a){return d(a)||e()||f()}var h=[],i=a.Deferred();return c.data("bs.validator.deferred")&&c.data("bs.validator.deferred").reject(),c.data("bs.validator.deferred",i),a.each(this.validators,a.proxy(function(a,d){var e=null;!b(c)&&!c.attr("required")||void 0===c.attr("data-"+a)&&"native"!=a||!(e=d.call(this,c))||(e=g(a)||e,!~h.indexOf(e)&&h.push(e))},this)),!h.length&&b(c)&&c.attr("data-remote")?this.defer(c,function(){var d={};d[c.attr("name")]=b(c),a.get(c.attr("data-remote"),d).fail(function(a,b,c){h.push(g("remote")||c)}).always(function(){i.resolve(h)})}):i.resolve(h),i.promise()},d.prototype.validate=function(){var b=this;return a.when(this.$inputs.map(function(){return b.validateInput(a(this),!1)})).then(function(){b.toggleSubmit(),b.focusError()}),this},d.prototype.focusError=function(){if(this.options.focus){var b=this.$element.find(".has-error:first :input");0!==b.length&&(a("html, body").animate({scrollTop:b.offset().top-d.FOCUS_OFFSET},250),b.focus())}},d.prototype.showErrors=function(b){var c=this.options.html?"html":"text",d=b.data("bs.validator.errors"),e=b.closest(".form-group"),f=e.find(".help-block.with-errors"),g=e.find(".form-control-feedback");d.length&&(d=a("<ul/>").addClass("list-unstyled").append(a.map(d,function(b){return a("<li/>")[c](b)})),void 0===f.data("bs.validator.originalContent")&&f.data("bs.validator.originalContent",f.html()),f.empty().append(d),e.addClass("has-error has-danger"),e.hasClass("has-feedback")&&g.removeClass(this.options.feedback.success)&&g.addClass(this.options.feedback.error)&&e.removeClass(this.options.feedback.success)&&e.removeClass("has-success"))},d.prototype.clearErrors=function(a){var c=a.closest(".form-group"),d=c.find(".help-block.with-errors"),e=c.find(".form-control-feedback");d.html(d.data("bs.validator.originalContent")),c.removeClass("has-error has-danger has-success"),c.hasClass("has-feedback")&&e.removeClass(this.options.feedback.error)&&e.removeClass(this.options.feedback.success)&&b(a)&&e.addClass(this.options.feedback.success)&&c.addClass("has-success")},d.prototype.hasErrors=function(){function b(){return!!(a(this).data("bs.validator.errors")||[]).length}return!!this.$inputs.filter(b).length},d.prototype.isIncomplete=function(){function c(){var c=b(a(this));return!("string"==typeof c?a.trim(c):c)}return!!this.$inputs.filter("[required]").filter(c).length},d.prototype.onSubmit=function(a){this.validate(),(this.isIncomplete()||this.hasErrors())&&a.preventDefault()},d.prototype.toggleSubmit=function(){this.options.disable&&this.$btn.toggleClass("disabled",this.isIncomplete()||this.hasErrors())},d.prototype.defer=function(b,c){return c=a.proxy(c,this,b),this.options.delay?(window.clearTimeout(b.data("bs.validator.timeout")),void b.data("bs.validator.timeout",window.setTimeout(c,this.options.delay))):c()},d.prototype.reset=function(){return this.$element.find(".form-control-feedback").removeClass(this.options.feedback.error).removeClass(this.options.feedback.success),this.$inputs.removeData(["bs.validator.errors","bs.validator.deferred"]).each(function(){var b=a(this),c=b.data("bs.validator.timeout");window.clearTimeout(c)&&b.removeData("bs.validator.timeout")}),this.$element.find(".help-block.with-errors").each(function(){var b=a(this),c=b.data("bs.validator.originalContent");b.removeData("bs.validator.originalContent").html(c)}),this.$btn.removeClass("disabled"),this.$element.find(".has-error, .has-danger, .has-success").removeClass("has-error has-danger has-success"),this},d.prototype.destroy=function(){return this.reset(),this.$element.removeAttr("novalidate").removeData("bs.validator").off(".bs.validator"),this.$inputs.off(".bs.validator"),this.options=null,this.validators=null,this.$element=null,this.$btn=null,this.$inputs=null,this};var e=a.fn.validator;a.fn.validator=c,a.fn.validator.Constructor=d,a.fn.validator.noConflict=function(){return a.fn.validator=e,this},a(window).on("load",function(){a('form[data-toggle="validator"]').each(function(){var b=a(this);c.call(b,b.data())})})}(jQuery);
</script>

{{-- B. Custom Logic --}}
<script>
    $(document).ready(function() {
        // Initialize Validator
        $('#formUsulan').validator();
    });

    // --- 1. Modal Logic ---
    function openConfirmation() {
        // Check Validity of Tab 3 before opening modal
        if (validateTab('tab-book')) {
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();
        }
    }

    // --- 2. AJAX Submission ---
function submitUsulanAjax() {
        const formUsulan = document.getElementById('formUsulan');
        const btnOpenModal = document.getElementById('btnOpenModal');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const msgSubmit = document.getElementById('msgSubmit');
        
        // Hide Modal
        var modalEl = document.getElementById('confirmationModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        // UI Loading State
        msgSubmit.classList.add('d-none');
        msgSubmit.textContent = '';
        loadingIndicator.classList.remove('d-none');
        btnOpenModal.disabled = true;

        // Prepare Data
        const formData = new FormData(formUsulan);
        const jsonData = {};
        formData.forEach((value, key) => { jsonData[key] = value; });

        fetch(formUsulan.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(response => {
            loadingIndicator.classList.add('d-none');
            btnOpenModal.disabled = false;
            msgSubmit.classList.remove('d-none');

            if (response.status === 200 || response.status === 201) {
                // SUCCESS
                msgSubmit.textContent = response.body.message;
                msgSubmit.classList.add('alert-success');
                msgSubmit.classList.remove('alert-danger');
                
                formUsulan.reset();
                
                // --- FIX: UPDATE TABLE WITHOUT RELOADING ---
                if(response.body.new_data) {
                    addHistoryRow(response.body.new_data);
                }
                
                // Switch back to Tab 1 (Optional)
                switchTab('tab-attention'); 

            } else {
                // ERROR
                let errorMessage = response.body.message || 'Terjadi kesalahan.';
                if (response.body.errors) {
                    errorMessage = Object.values(response.body.errors).flat().join('\n');
                } 
                msgSubmit.textContent = errorMessage;
                msgSubmit.classList.add('alert-danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('d-none');
            btnOpenModal.disabled = false;
            msgSubmit.classList.remove('d-none');
            msgSubmit.textContent = 'Terjadi kesalahan jaringan.';
            msgSubmit.classList.add('alert-danger');
        });
    }

    // --- NEW FUNCTION: Add Row to Table ---
    function addHistoryRow(data) {
        const tableBody = document.querySelector('.history-table tbody');
        
        // 1. Check if empty state exists, remove it
        const emptyRow = tableBody.querySelector('tr td[colspan="4"]');
        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }

        // 2. Create Badge HTML
        let statusBadge = '';
        if (data.status_req == 0) statusBadge = '<span class="badge bg-warning text-dark rounded-pill">Menunggu</span>';
        else if (data.status_req == 1) statusBadge = '<span class="badge bg-success rounded-pill">Disetujui</span>';
        else statusBadge = '<span class="badge bg-danger rounded-pill">Ditolak</span>';

        // 3. Build Row HTML
        const newRowHTML = `
            <tr class="table-success"> <td>
                    <div class="fw-bold text-dark">${data.judul_buku}</div>
                    <small class="text-muted">${data.penulis_buku}</small>
                </td>
                <td>${data.nama_req}</td>
                <td>${data.date_fmt}</td>
                <td class="text-center">${statusBadge}</td>
            </tr>
        `;

        // 4. Prepend to table (Add to top)
        tableBody.insertAdjacentHTML('afterbegin', newRowHTML);
        
        // 5. Remove green flash effect after 2 seconds (Optional animation)
        setTimeout(() => {
            const row = tableBody.querySelector('tr.table-success');
            if(row) row.classList.remove('table-success');
        }, 2000);
    }

    // --- 3. Validation Helpers ---
    function validateTab(tabId) {
        var $currentTab = $('#' + tabId);
        var $inputs = $currentTab.find('input, select, textarea');
        var isValid = true;

        $inputs.each(function() {
            var $el = $(this);
            if($el.is(':hidden')) return; // Skip hidden inputs
            if (!this.checkValidity()) isValid = false;
            $el.trigger('input'); // Force Validator check
            if ($el.closest('.form-group').hasClass('has-error')) isValid = false;
        });

        if (!isValid) {
            var $firstError = $currentTab.find('.has-error').first();
            if($firstError.length) {
                $('html, body').animate({ scrollTop: $firstError.offset().top - 100 }, 500);
            }
        }
        return isValid;
    }

    function validateAndNext(currentTabId, nextTabId) {
        if(validateTab(currentTabId)) {
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

    function toggleNextButton() {
        const checkbox = document.getElementById('agreementCheck');
        const btn = document.getElementById('btnToStep2');
        btn.disabled = !checkbox.checked;
    }

    function toggleIdentity(type) {
        const inputNim = document.getElementById('input_nim');
        const inputNip = document.getElementById('input_nip');
        if(type === 'nim') {
            inputNim.style.display = 'block'; inputNim.setAttribute('required', 'required');
            inputNip.style.display = 'none'; inputNip.removeAttribute('required'); inputNip.value = '';
        } else {
            inputNip.style.display = 'block'; inputNip.setAttribute('required', 'required');
            inputNim.style.display = 'none'; inputNim.removeAttribute('required'); inputNim.value = '';
        }
        $('#formUsulan').validator('update');
    }

    function autofillForm() {
        document.getElementById('agreementCheck').checked = true;
        toggleNextButton(); 
        document.querySelector('input[name="nama_req"]').value = "Mahasiswa Teladan";
        document.querySelector('input[name="email_req"]').value = "mahasiswa@pcr.ac.id";
        document.getElementById('identity_type').value = 'nim'; toggleIdentity('nim');
        document.querySelector('input[name="nim"]').value = "2355301999";
        const prodi = document.querySelector('select[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;
        document.querySelector('input[name="judul_buku"]').value = "Clean Code";
        document.querySelector('input[name="penulis_buku"]').value = "Robert C. Martin";
        document.querySelector('input[name="tahun_terbit"]').value = "2008";
        document.querySelector('input[name="estimasi_harga"]').value = "450000";
        document.querySelector('input[name="link_pembelian"]').value = "https://amazon.com";
        document.querySelector('textarea[name="alasan_usulan"]').value = "Referensi Skripsi";
        
        $('#formUsulan').validator('update');
        $('#formUsulan').find('.form-control').trigger('input');
    }
</script>