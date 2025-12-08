{{-- author : mwy --}}
@props([
    'title' => 'Data',
    'table' => 'table',
    'form' => 'formData',
    'modal' => 'modalForm',
    'url_add' => '',
    'url_edit' => '',
    'url_update' => '',
    'url_delete' => '',
    'url_approve' => '',
    'url_reject' => '',
])

@push('scripts')
    <script>
        // ------------------------------------------------------------------------------------------------------------------------------------------------------------
        @if ($url_add)
            $(document).on('click', `.act-add`, function(e) {
                $('#{{ $modal }}-title').html('Tambah {{ $title }}')
                $('#{{ $form }}').attr('action', '{{ $url_add }}')
                resetForm('{{ $form }}')
                $('#{{ $modal }}').modal('show')
            })
        @endif

        @if ($url_edit)
            $(document).on('click', `#{{ $table }} .act-edit`, function(e) {
                $('#{{ $modal }}-title').html('Edit {{ $title }}')
                $('#{{ $form }}').attr('action', '{{ $url_update }}')
                resetForm('{{ $form }}')

                id = $(this).data('id')
                ajaxRequest({
                    link: '{{ $url_edit }}',
                    data: {
                        id: id
                    },
                    callback: function(origin, resp) {
                        data = resp.data

                        {!! $slot !!}

                        $('#{{ $modal }}').modal('show')
                    }
                })
            })
        @endif

        @if ($url_delete)
            $(document).on('click', `#{{ $table }} .act-delete`, function(e) {
                console.log("clicked delete");
                let data_id = $(this).data('id')
                let data = {
                    id: data_id
                }
                
                Swal.fire({
                    title: "Hapus data ?",
                    text: "Data yang sudah dihapus tidak dapat dikembalikan, pastikan data yang akan di hapus sudah sesuai",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Lanjutkan!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-light-danger",
                        cancelButton: "btn btn-light-dark"
                    },
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        ajaxRequest({
                            link: `{{ $url_delete }}`,
                            data: data,
                            swal_success: true,
                            callback: function cb(obj_origin, resp) {
                                if (resp.status) {
                                    $(`#{{ $table }}`).DataTable().ajax.reload(null, false)
                                }
                            }
                        })
                    }
                });
            })
        @endif

        @if ($url_update || $url_add)
            $(document).on('click', `#{{ $modal }} .act-save`, function(e) {
                $('#{{ $form }}').submit()
            })
        @endif

        @if ($url_update || $url_add)
            $(document).on('submit', `#{{ $form }}`, function(e) {
                e.preventDefault()

                ajaxRequest({
                    link: $('#{{ $form }}').attr('action'),
                    data: $('#{{ $form }}').serialize(),
                    callback: function(origin, resp) {
                        if (resp.status) {
                            $(`#{{ $table }}`).DataTable().ajax.reload(null, false)
                            $('#{{ $modal }}').modal('hide')
                        }
                    },
                    swal_success: true
                })
            })
        @endif

        // APPROVE (replace existing APPROVE block with this)
@if ($url_approve)
    $(document).on('click', `#{{ $table }} .act-approve`, function(e) {
        let id = $(this).data('id');

        resetForm('formApprove');
        $('#formApprove input[name="id"]').val(id);

        // Bootstrap 5 modal show
        const modalEl = document.getElementById('modalApprove');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });

    $(document).on('submit', `#formApprove`, function(e) {
        e.preventDefault();

        ajaxRequest({
            link: `{{ $url_approve }}`,
            data: $('#formApprove').serialize(),
            swal_success: true,
            callback: function(origin, resp) {
                if (resp.status) {
                    $(`#{{ $table }}`).DataTable().ajax.reload(null, false);

                    // Bootstrap 5 modal hide (get existing instance if any)
                    const modalEl = document.getElementById('modalApprove');
                    const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    inst.hide();
                }
            }
        });
    });
@endif

// REJECT (replace existing REJECT block with this)
@if ($url_reject)

// open modal
$(document).on('click', `#{{ $table }} .act-reject`, function () {
    let id = $(this).data('id');
    $('#modalReject input[name="reqbuku_id"]').val(id);
    $('#modalReject').modal('show');
});

// button confirm
$(document).on('click', '[jf-save="usulan_reject"]', function (e) {
    e.preventDefault();
    $('#formReject').trigger('submit');
});

// submit handler
$('#formReject').on('submit', function (e) {
    e.preventDefault();

    let reason = $(this).find('[name="reason"]').val().trim();
    if (!reason) {
        Swal.fire("Alasan wajib diisi", "", "warning");
        return;
    }

    let formData = new FormData(this);

    $.ajax({
        url: "{{ $url_reject }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#modalReject').modal('hide');
            Swal.fire("Berhasil", "Usulan ditolak.", "success");
            $('#{{ $table }}').DataTable().ajax.reload(null, false);
        }
    });
});

@endif



    </script>
@endpush
