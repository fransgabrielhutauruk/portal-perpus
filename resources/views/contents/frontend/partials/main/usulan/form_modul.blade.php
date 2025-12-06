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
                                    2. Data Dosen
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-book-btn" data-bs-target="#tab-book" type="button" role="tab" disabled>
                                    3. Data Modul
                                </button>
                            </li>
                        </ul>

                        {{-- FORM START (Added enctype) --}}
                        <form id="formUsulan" action="{{ data_get($content, 'form.action_url') }}" method="POST" enctype="multipart/form-data" data-toggle="validator">
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
                                        <p>Pastikan Anda telah memeriksa ketersediaan modul sebelumnya.</p>
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
                                                Saya akan mengisi data modul dengan benar.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="contact-form-btn">
                                        <button type="button" id="btnToStep2" class="btn-default" disabled onclick="switchTab('tab-user')">
                                            Selanjutnya
                                        </button>
                                    </div>
                                </div>

                                {{-- === TAB 2: DATA DOSEN === --}}
                                <div class="tab-pane fade" id="tab-user" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Nama Dosen</label>
                                                <input type="text" name="nama_dosen" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Inisial</label>
                                                <input type="text" name="inisial_dosen" class="form-control" placeholder="Contoh: AA" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Email</label>
                                                <input type="email" name="email_dosen" class="form-control" required data-error="Email tidak valid">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">NIP</label>                                                
                                                <input type="number" name="nip" class="form-control" placeholder="NIP" required data-error="NIP Wajib diisi">
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

                                {{-- === TAB 3: DATA MODUL === --}}
                                <div class="tab-pane fade" id="tab-book" role="tabpanel">
                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Nama Mata Kuliah</label>
                                        <input type="text" name="nama_mata_kuliah" class="form-control" required data-error="Wajib diisi">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Judul Modul</label>
                                                <input type="text" name="judul_modul" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Penulis Modul</label>
                                                <input type="text" name="penulis_modul" class="form-control" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Tahun Modul</label>
                                                <input type="number" name="tahun_modul" class="form-control" placeholder="YYYY" required data-error="Wajib diisi">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase d-block">Jenis Modul</label>
                                                <div class="btn-group w-100" role="group">
                                                    <input type="radio" class="btn-check" name="praktikum" id="typeTeori" value="0" checked>
                                                    <label class="btn btn-outline-primary" for="typeTeori">Teori</label>
                                                
                                                    <input type="radio" class="btn-check" name="praktikum" id="typePraktikum" value="1">
                                                    <label class="btn btn-outline-primary" for="typePraktikum">Praktikum</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold text-muted small text-uppercase">Jumlah Dibutuhkan</label>
                                                <input type="number" name="jumlah_dibutuhkan" class="form-control" value="0">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Upload File Modul (Opsional)</label>
                                        <input type="file" name="file" class="form-control">
                                        <small class="text-muted">Format: PDF/DOCX. Kosongkan jika belum ada file.</small>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-2 fw-bold text-muted small text-uppercase">Deskripsi Kebutuhan</label>
                                        <textarea name="deskripsi_kebutuhan" class="form-control" rows="3" placeholder="Jelaskan detail kebutuhan atau revisi..."></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    {{-- ACTION BUTTONS --}}
                                    <div class="col-lg-12 mt-4">
                                        <div class="contact-form-btn d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="switchTab('tab-user')">Sebelumnya</button>
                                            
                                            <div>
                                                {{-- Triggers Modal, NOT Submit --}}
                                                <button type="button" id="btnOpenModal" class="btn-default" onclick="openConfirmation()">
                                                    Kirim Permintaan
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
                            <h3>Riwayat Request Modul</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>Modul / Matkul</th>
                                        <th>Dosen</th>
                                        <th>Jenis</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(data_get($content, 'history', []) as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->judul_modul }}</div>
                                            <small class="text-muted">{{ $item->nama_mata_kuliah }}</small>
                                        </td>
                                        <td>
                                            {{ $item->nama_dosen }} <br>
                                            <small class="text-muted">({{ $item->inisial_dosen }})</small>
                                        </td>
                                        <td>
                                            @if($item->praktikum)
                                                <span class="badge bg-info text-dark">Praktikum</span>
                                            @else
                                                <span class="badge bg-light text-dark border">Teori</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary rounded-pill">{{ $item->status ?? 'Pending' }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data request.</td></tr>
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
                        <h3>Pengajuan Ditutup</h3>
                        <p class="mb-4">Saat ini tidak ada periode pengajuan modul yang aktif.</p>
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
                    <i class="fa-solid fa-circle-question me-2"></i> Konfirmasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-secondary fs-6">
                    Apakah Anda yakin data modul dan file yang dilampirkan sudah benar? <br>
                    Data yang dikirim tidak dapat diubah kembali.
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

{{-- A. Validator Plugin (Kept as is) --}}
<script>
/*! Validator v0.11.9 by @1000hz */
+function(a){"use strict";function b(b){return b.is('[type="checkbox"]')?b.prop("checked"):b.is('[type="radio"]')?!!a('[name="'+b.attr("name")+'"]:checked').length:b.is("select[multiple]")?(b.val()||[]).length:b.val()}function c(b){return this.each(function(){var c=a(this),e=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b),f=c.data("bs.validator");(f||"destroy"!=b)&&(f||c.data("bs.validator",f=new d(this,e)),"string"==typeof b&&f[b]())})}var d=function(c,e){this.options=e,this.validators=a.extend({},d.VALIDATORS,e.custom),this.$element=a(c),this.$btn=a('button[type="submit"], input[type="submit"]').filter('[form="'+this.$element.attr("id")+'"]').add(this.$element.find('input[type="submit"], button[type="submit"]')),this.update(),this.$element.on("input.bs.validator change.bs.validator focusout.bs.validator",a.proxy(this.onInput,this)),this.$element.on("submit.bs.validator",a.proxy(this.onSubmit,this)),this.$element.on("reset.bs.validator",a.proxy(this.reset,this)),this.$element.find("[data-match]").each(function(){var c=a(this),d=c.attr("data-match");a(d).on("input.bs.validator",function(){b(c)&&c.trigger("input.bs.validator")})}),this.$inputs.filter(function(){return b(a(this))&&!a(this).closest(".has-error").length}).trigger("focusout"),this.$element.attr("novalidate",!0)};d.VERSION="0.11.9",d.INPUT_SELECTOR=':input:not([type="hidden"], [type="submit"], [type="reset"], button)',d.FOCUS_OFFSET=20,d.DEFAULTS={delay:500,html:!1,disable:!0,focus:!0,custom:{},errors:{match:"Does not match",minlength:"Not long enough"},feedback:{success:"glyphicon-ok",error:"glyphicon-remove"}},d.VALIDATORS={"native":function(a){var b=a[0];return b.checkValidity?!b.checkValidity()&&!b.validity.valid&&(b.validationMessage||"error!"):void 0},match:function(b){var c=b.attr("data-match");return b.val()!==a(c).val()&&d.DEFAULTS.errors.match},minlength:function(a){var b=a.attr("data-minlength");return a.val().length<b&&d.DEFAULTS.errors.minlength}},d.prototype.update=function(){var b=this;return this.$inputs=this.$element.find(d.INPUT_SELECTOR).add(this.$element.find('[data-validate="true"]')).not(this.$element.find('[data-validate="false"]').each(function(){b.clearErrors(a(this))})),this.toggleSubmit(),this},d.prototype.onInput=function(b){var c=this,d=a(b.target),e="focusout"!==b.type;this.$inputs.is(d)&&this.validateInput(d,e).done(function(){c.toggleSubmit()})},d.prototype.validateInput=function(c,d){var e=(b(c),c.data("bs.validator.errors"));c.is('[type="radio"]')&&(c=this.$element.find('input[name="'+c.attr("name")+'"]'));var f=a.Event("validate.bs.validator",{relatedTarget:c[0]});if(this.$element.trigger(f),!f.isDefaultPrevented()){var g=this;return this.runValidators(c).done(function(b){c.data("bs.validator.errors",b),b.length?d?g.defer(c,g.showErrors):g.showErrors(c):g.clearErrors(c),e&&b.toString()===e.toString()||(f=b.length?a.Event("invalid.bs.validator",{relatedTarget:c[0],detail:b}):a.Event("valid.bs.validator",{relatedTarget:c[0],detail:e}),g.$element.trigger(f)),g.toggleSubmit(),g.$element.trigger(a.Event("validated.bs.validator",{relatedTarget:c[0]}))})}},d.prototype.runValidators=function(c){function d(a){return c.attr("data-"+a+"-error")}function e(){var a=c[0].validity;return a.typeMismatch?c.attr("data-type-error"):a.patternMismatch?c.attr("data-pattern-error"):a.stepMismatch?c.attr("data-step-error"):a.rangeOverflow?c.attr("data-max-error"):a.rangeUnderflow?c.attr("data-min-error"):a.valueMissing?c.attr("data-required-error"):null}function f(){return c.attr("data-error")}function g(a){return d(a)||e()||f()}var h=[],i=a.Deferred();return c.data("bs.validator.deferred")&&c.data("bs.validator.deferred").reject(),c.data("bs.validator.deferred",i),a.each(this.validators,a.proxy(function(a,d){var e=null;!b(c)&&!c.attr("required")||void 0===c.attr("data-"+a)&&"native"!=a||!(e=d.call(this,c))||(e=g(a)||e,!~h.indexOf(e)&&h.push(e))},this)),!h.length&&b(c)&&c.attr("data-remote")?this.defer(c,function(){var d={};d[c.attr("name")]=b(c),a.get(c.attr("data-remote"),d).fail(function(a,b,c){h.push(g("remote")||c)}).always(function(){i.resolve(h)})}):i.resolve(h),i.promise()},d.prototype.validate=function(){var b=this;return a.when(this.$inputs.map(function(){return b.validateInput(a(this),!1)})).then(function(){b.toggleSubmit(),b.focusError()}),this},d.prototype.focusError=function(){if(this.options.focus){var b=this.$element.find(".has-error:first :input");0!==b.length&&(a("html, body").animate({scrollTop:b.offset().top-d.FOCUS_OFFSET},250),b.focus())}},d.prototype.showErrors=function(b){var c=this.options.html?"html":"text",d=b.data("bs.validator.errors"),e=b.closest(".form-group"),f=e.find(".help-block.with-errors"),g=e.find(".form-control-feedback");d.length&&(d=a("<ul/>").addClass("list-unstyled").append(a.map(d,function(b){return a("<li/>")[c](b)})),void 0===f.data("bs.validator.originalContent")&&f.data("bs.validator.originalContent",f.html()),f.empty().append(d),e.addClass("has-error has-danger"),e.hasClass("has-feedback")&&g.removeClass(this.options.feedback.success)&&g.addClass(this.options.feedback.error)&&e.removeClass(this.options.feedback.success)&&e.removeClass("has-success"))},d.prototype.clearErrors=function(a){var c=a.closest(".form-group"),d=c.find(".help-block.with-errors"),e=c.find(".form-control-feedback");d.html(d.data("bs.validator.originalContent")),c.removeClass("has-error has-danger has-success"),c.hasClass("has-feedback")&&e.removeClass(this.options.feedback.error)&&e.removeClass(this.options.feedback.success)&&b(a)&&e.addClass(this.options.feedback.success)&&c.addClass("has-success")},d.prototype.hasErrors=function(){function b(){return!!(a(this).data("bs.validator.errors")||[]).length}return!!this.$inputs.filter(b).length},d.prototype.isIncomplete=function(){function c(){var c=b(a(this));return!("string"==typeof c?a.trim(c):c)}return!!this.$inputs.filter("[required]").filter(c).length},d.prototype.onSubmit=function(a){this.validate(),(this.isIncomplete()||this.hasErrors())&&a.preventDefault()},d.prototype.toggleSubmit=function(){this.options.disable&&this.$btn.toggleClass("disabled",this.isIncomplete()||this.hasErrors())},d.prototype.defer=function(b,c){return c=a.proxy(c,this,b),this.options.delay?(window.clearTimeout(b.data("bs.validator.timeout")),void b.data("bs.validator.timeout",window.setTimeout(c,this.options.delay))):c()},d.prototype.reset=function(){return this.$element.find(".form-control-feedback").removeClass(this.options.feedback.error).removeClass(this.options.feedback.success),this.$inputs.removeData(["bs.validator.errors","bs.validator.deferred"]).each(function(){var b=a(this),c=b.data("bs.validator.timeout");window.clearTimeout(c)&&b.removeData("bs.validator.timeout")}),this.$element.find(".help-block.with-errors").each(function(){var b=a(this),c=b.data("bs.validator.originalContent");b.removeData("bs.validator.originalContent").html(c)}),this.$btn.removeClass("disabled"),this.$element.find(".has-error, .has-danger, .has-success").removeClass("has-error has-danger has-success"),this},d.prototype.destroy=function(){return this.reset(),this.$element.removeAttr("novalidate").removeData("bs.validator").off(".bs.validator"),this.$inputs.off(".bs.validator"),this.options=null,this.validators=null,this.$element=null,this.$btn=null,this.$inputs=null,this};var e=a.fn.validator;a.fn.validator=c,a.fn.validator.Constructor=d,a.fn.validator.noConflict=function(){return a.fn.validator=e,this},a(window).on("load",function(){a('form[data-toggle="validator"]').each(function(){var b=a(this);c.call(b,b.data())})})}(jQuery);
</script>

{{-- B. Custom Logic --}}
<script>
    $(document).ready(function() {
        $('#formUsulan').validator();
    });

    // --- 1. Modal Logic ---
    function openConfirmation() {
        if (validateTab('tab-book')) {
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();
        }
    }

    // --- 2. AJAX Submission (MODIFIED FOR FILE UPLOAD) ---
    function submitUsulanAjax() {
        const formUsulan = document.getElementById('formUsulan');
        const btnOpenModal = document.getElementById('btnOpenModal');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const msgSubmit = document.getElementById('msgSubmit');
        
        var modalEl = document.getElementById('confirmationModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        msgSubmit.classList.add('d-none');
        msgSubmit.textContent = '';
        loadingIndicator.classList.remove('d-none');
        btnOpenModal.disabled = true;

        // --- CHANGE: USE FormData for File Upload ---
        const formData = new FormData(formUsulan);

        fetch(formUsulan.action, {
            method: 'POST',
            headers: {
                // 'Content-Type': 'application/json', // REMOVE THIS for FormData
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData // SEND formData directly, not JSON string
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(response => {
            loadingIndicator.classList.add('d-none');
            btnOpenModal.disabled = false;
            msgSubmit.classList.remove('d-none');

            if (response.status === 200 || response.status === 201) {
                msgSubmit.textContent = response.body.message;
                msgSubmit.classList.add('alert-success');
                msgSubmit.classList.remove('alert-danger');
                
                formUsulan.reset();
                
                if(response.body.new_data) {
                    addHistoryRow(response.body.new_data);
                }
                
                switchTab('tab-attention'); 

            } else {
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

    // --- NEW FUNCTION: Add Row to Table (Updated for Modul) ---
    function addHistoryRow(data) {
        const tableBody = document.querySelector('.history-table tbody');
        
        const emptyRow = tableBody.querySelector('tr td[colspan="4"]');
        if (emptyRow) {
            emptyRow.closest('tr').remove();
        }

        let jenisBadge = data.praktikum == 1 
            ? '<span class="badge bg-info text-dark">Praktikum</span>' 
            : '<span class="badge bg-light text-dark border">Teori</span>';

        const newRowHTML = `
            <tr class="table-success"> 
                <td>
                    <div class="fw-bold text-dark">${data.judul_modul}</div>
                    <small class="text-muted">${data.nama_mata_kuliah}</small>
                </td>
                <td>
                    ${data.nama_dosen} <br>
                    <small class="text-muted">(${data.inisial_dosen})</small>
                </td>
                <td>${jenisBadge}</td>
                <td class="text-center"><span class="badge bg-secondary rounded-pill">${data.status}</span></td>
            </tr>
        `;

        tableBody.insertAdjacentHTML('afterbegin', newRowHTML);
        
        setTimeout(() => {
            const row = tableBody.querySelector('tr.table-success');
            if(row) row.classList.remove('table-success');
        }, 2000);
    }

    // --- 3. Validation Helpers (Kept as is) ---
    function validateTab(tabId) {
        var $currentTab = $('#' + tabId);
        var $inputs = $currentTab.find('input, select, textarea');
        var isValid = true;

        $inputs.each(function() {
            var $el = $(this);
            if($el.is(':hidden')) return; 
            if (!this.checkValidity()) isValid = false;
            $el.trigger('input'); 
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

    // --- 5. Autofill (Updated for Modul) ---
    function autofillForm() {
        document.getElementById('agreementCheck').checked = true;
        toggleNextButton(); 
        
        // Tab 2
        document.querySelector('input[name="nama_dosen"]').value = "Budi Santoso, M.T.";
        document.querySelector('input[name="inisial_dosen"]').value = "BS";
        document.querySelector('input[name="email_dosen"]').value = "budi@pcr.ac.id";
        document.querySelector('input[name="nip"]').value = "19880101202001";
        const prodi = document.querySelector('select[name="prodi_id"]');
        if (prodi.options.length > 1) prodi.selectedIndex = 1;

        // Tab 3
        document.querySelector('input[name="nama_mata_kuliah"]').value = "Pemrograman Web";
        document.querySelector('input[name="judul_modul"]').value = "Modul Praktikum Laravel 11";
        document.querySelector('input[name="penulis_modul"]').value = "Tim Dosen Web";
        document.querySelector('input[name="tahun_modul"]').value = "2025";
        document.querySelector('input[name="jumlah_dibutuhkan"]').value = "40";
        document.getElementById('typePraktikum').checked = true;
        document.querySelector('textarea[name="deskripsi_kebutuhan"]').value = "Mohon dicetak warna untuk bagian diagram.";
        
        $('#formUsulan').validator('update');
        $('#formUsulan').find('.form-control').trigger('input');
    }
</script>