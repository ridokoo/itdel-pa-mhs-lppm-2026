<div>
<style>

    /* =========================================================
        GLOBAL STYLE
    ========================================================== */

    .page-header{
        background-color:#ffffff;
    }

    .page-title{
        color:#1a1a1a;
    }

    .card{
        background-color:#ffffff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        box-shadow:0 4px 14px rgba(0,0,0,0.04);
    }

    .card-body{
        background-color:#ffffff;
    }


    /* =========================================================
        FORM
    ========================================================== */

    .form-label{
        color:#374151;
        font-weight:600;
        font-size:13.5px;
        margin-bottom:6px;
    }

    .form-control,
    .form-select{
        background-color:#ffffff;
        color:#374151;
        border:1px solid #e5e7eb;
        border-radius:10px;
        font-size:14px;
        padding:10px 14px;
        transition:all .2s ease;
    }

    .form-control::placeholder{
        color:#9ca3af;
    }

    .form-control:focus,
    .form-select:focus{
        border-color:#0070C0 !important;
        box-shadow:0 0 0 .2rem rgba(32,201,151,.15) !important;
    }


    /* =========================================================
        BUTTON
    ========================================================== */

    .btn-primary{
        background-color:#0070C0 !important;
        border-color:#0070C0 !important;
        border-radius:10px;
        font-weight:600;
        transition:all .2s ease;
    }

    .btn-primary:hover{
        background-color:#1bad82 !important;
        border-color:#1bad82 !important;
    }

    .btn-light{
        background-color:#f9fafb;
        border-color:#e5e7eb;
        border-radius:10px;
    }


    /* =========================================================
        TABLE
    ========================================================== */

    .table thead th{
        background-color:#f8f9fa;
        border-bottom:2px solid #eeeeee;
        font-weight:700;
        color:#374151;
        padding:14px;
    }

    .table tbody td{
        padding:14px;
        vertical-align:middle;
    }

    .table tbody tr{
        transition:all .2s ease;
    }

    .table tbody tr:hover{
        background-color:#f0fdf4;
    }


    /* =========================================================
        SWEET ALERT CUSTOM
    ========================================================== */

    .swal2-popup{
        width:22rem !important;
        padding:1.8rem !important;
        border-radius:20px !important;
        background:#ffffff !important;
        color:#333333 !important;
    }

    .swal2-title{
        font-size:1.7rem !important;
        font-weight:700 !important;
        color:#1f2937 !important;
        margin-top:12px !important;
        margin-bottom:6px !important;
    }

    .swal2-html-container{
        display:block !important;
        font-size:15px !important;
        color:#6b7280 !important;
        line-height:1.6 !important;
        text-align:center !important;
        margin-top:8px !important;
        margin-bottom:1rem !important;
    }

    .swal2-confirm{
        background-color:#0070C0 !important;
        border:none !important;
        border-radius:10px !important;
        padding:10px 24px !important;
        font-weight:600 !important;
        box-shadow:none !important;
    }

    .swal2-confirm:focus{
        box-shadow:none !important;
    }

    .swal2-cancel{
        border-radius:10px !important;
        padding:10px 24px !important;
        font-weight:600 !important;
    }


    /* =========================================================
        ICON SWEET ALERT
    ========================================================== */

    .swal2-icon{
        width:95px !important;
        height:95px !important;
        margin:10px auto 12px auto !important;
        border-width:4px !important;
    }

    .swal2-icon .swal2-icon-content{
        font-size:4rem !important;
    }


    /* SUCCESS */

    .swal2-icon.swal2-success{
        border-color:#0070C0 !important;
        color:#0070C0 !important;
    }

    .swal2-success-ring{
        border:4px solid rgba(32,201,151,.25) !important;
    }

    .swal2-icon.swal2-success [class^='swal2-success-line']{
        background-color:#0070C0 !important;
        height:6px !important;
        border-radius:10px !important;
    }

    .swal2-success-line-tip{
        width:28px !important;
        left:14px !important;
        top:46px !important;
    }

    .swal2-success-line-long{
        width:45px !important;
        right:10px !important;
        top:38px !important;
    }


    /* WARNING */

    .swal2-icon.swal2-warning{
        border-color:#f8bb86 !important;
        color:#f8bb86 !important;
    }


    /* ERROR */

    .swal2-icon.swal2-error{
        border-color:#ef4444 !important;
        color:#ef4444 !important;
    }


    /* QUESTION */

    .swal2-icon.swal2-question{
        border-color:#0070C0 !important;
        color:#0070C0 !important;
    }


    /* TIMER */

    .swal2-timer-progress-bar{
        background-color:#0070C0 !important;
    }

</style>


    <!-- =========================================================
        HEADER
    ========================================================== -->

    <div class="page-header border-bottom mb-4">

        <div class="page-header-content d-flex align-items-center">

            <div class="d-flex py-2">

                <h4 class="page-title mb-0 fw-bold">

                    <i class="ph-bold ph-graduation-cap me-2 text-success"></i>

                    @if($showForm && $isEdit)
                        Edit Fakultas
                    @elseif($showForm)
                        Tambah Fakultas Baru
                    @else
                        Manajemen Fakultas
                    @endif

                </h4>

            </div>

            <div class="ms-auto">

                @if(!$showForm)

                    <button
                        wire:click="showCreate"
                        class="btn btn-primary shadow-sm px-3">

                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Fakultas

                    </button>

                @else

                    <button
                        wire:click="resetFields"
                        class="btn btn-light border px-3">

                        <i class="ph-bold ph-arrow-left me-2"></i>
                        Kembali

                    </button>

                @endif

            </div>

        </div>

    </div>


    <!-- =========================================================
        CONTENT
    ========================================================== -->

    <div class="content">

        @if(!$showForm)

            <!-- TABLE -->
            <div class="card">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">No</th>
                                <th class="text-center">Nama Fakultas</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($faculties as $f)

                                <tr
                                    class="align-middle"
                                    wire:key="fakultas-{{ $f->id }}">

                                    <td class="text-center text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="fw-bold">
                                        {{ $f->name }}
                                    </td>

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">

                                            <!-- EDIT -->
                                            <button
                                                wire:click="showEdit('{{ $f->id }}')"
                                                class="btn btn-outline-primary btn-sm rounded-pill border-0"
                                                title="Edit">

                                                <i class="ph-bold ph-pencil"></i>

                                            </button>

                                            <!-- DELETE -->
                                            <button
                                                type="button"
                                                onclick="confirmDelete('{{ $f->id }}')"
                                                class="btn btn-outline-danger btn-sm rounded-pill border-0"
                                                title="Hapus">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3" class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-graduation-cap fs-2 d-block mb-2 opacity-25"></i>

                                        Belum ada data fakultas.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            <!-- FORM -->
            <div class="card">

                <div class="card-body p-4">

                    <form wire:submit.prevent="{{ $isEdit ? 'confirmUpdate' : 'store' }}">

                        <div class="mb-4">

                            <label class="form-label">
                                Nama Fakultas
                            </label>

                            <input
                                type="text"
                                wire:model="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Fakultas Informatika & Teknik Elektro">

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        @if($isEdit)

                            <div
                                class="alert border-0 mb-4 d-flex align-items-center"
                                style="background:#e8f5e9; color:#2e7d32;">

                                <i class="ph-bold ph-info me-2"></i>

                                <small>
                                    Perubahan nama fakultas akan mempengaruhi data prodi terkait.
                                </small>

                            </div>

                        @endif

                        <div class="border-top pt-3 mt-2 d-flex justify-content-end gap-2">

                            <button
                                type="button"
                                wire:click="resetFields"
                                class="btn btn-link text-muted fw-bold text-decoration-none">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary px-4 shadow-sm"
                                wire:loading.attr="disabled"
                                wire:target="store, update">

                                <span wire:loading.remove wire:target="store, update">

                                    <i class="ph-bold ph-floppy-disk me-2"></i>

                                    {{ $isEdit ? 'Update' : 'Simpan Data' }}

                                </span>

                                <span wire:loading wire:target="store, update">

                                    Menyimpan...

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>


    <!-- =========================================================
        SWEET ALERT SCRIPT
    ========================================================== -->

@script
<script>

    // ================= DELETE =================
    window.confirmDelete = (id) => {

        Swal.fire({

            title: 'Hapus data?',
            html: `<div>Data yang dihapus tidak dapat dikembalikan.</div>`,
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',

            reverseButtons: true

        }).then((result) => {

            if (result.isConfirmed) {

                $wire.delete(id);

            }

        });

    };


    // ================= UPDATE =================
    $wire.on('triggerUpdateConfirm', () => {

        Swal.fire({

            title: 'Update data?',
            html: `<div>Perubahan data akan disimpan.</div>`,
            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#0070C0',
            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Update',
            cancelButtonText: 'Batal'

        }).then((result) => {

            if (result.isConfirmed) {

                $wire.update();

            }

        });

    });


    // ================= NOTIFICATION =================
    Livewire.on('swal:modal', (event) => {

        let data = event[0] ?? event;

        Swal.fire({

            title: data.title ?? 'Info',

            html: `<div>${data.text ?? ''}</div>`,

            icon: data.icon ?? 'info',

            confirmButtonText: 'OK',

            confirmButtonColor: '#0070C0',

            timer: 2500,
            timerProgressBar: true

        });

    });

</script>
@endscript

</div>



