<div>
    <style>

    /* ================= SWEET ALERT ================= */

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
    }

    .swal2-html-container{
        display:block !important;
        text-align:center !important;

        font-size:15px !important;
        color:#6b7280 !important;

        line-height:1.6 !important;

        margin-top:8px !important;
        margin-bottom:1rem !important;
    }

    .swal2-confirm{
        background:#0070C0 !important;
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

    /* ICON */

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
        background:#0070C0 !important;
    }

</style>

    <!-- HEADER -->
    <div class="page-header border-bottom mb-4">
        <div class="page-header-content d-flex align-items-center">

            <h4 class="page-title mb-0 fw-bold">

                <i class="ph-bold ph-graduation-cap me-2 text-success"></i>

                @if($showForm && $isEdit)
                    Edit Program Studi
                @elseif($showForm)
                    Tambah Program Studi
                @else
                    Manajemen Program Studi
                @endif

            </h4>

            <div class="ms-auto">

                @if(!$showForm)

                    <button
                        wire:click="create"
                        class="btn btn-primary shadow-sm">

                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Program Studi

                    </button>

                @else

                    <button
                        wire:click="resetFields"
                        class="btn btn-light border">

                        <i class="ph-bold ph-arrow-left me-2"></i>
                        Kembali

                    </button>

                @endif

            </div>

        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        @if(!$showForm)

            <!-- TABLE -->
            <div class="card">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fakultas</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($programs as $p)

                                <tr wire:key="program-{{ $p->id }}">

                                    <td class="text-center">

                                        {{ $loop->iteration }}

                                    </td>

                                    <td>

                                        <span
                                            style="
                                                background: linear-gradient(135deg, #eaf4ff, #dceeff);
                                                color: #0b5ea8;
                                                border: 1px solid #b8d9f5;
                                                border-radius: 10px;
                                                padding: 7px 14px;
                                                font-size: 12px;
                                                font-weight: 600;
                                                display: inline-flex;
                                                align-items: center;
                                                gap: 6px;
                                                box-shadow: 0 2px 6px rgba(0, 112, 192, 0.08);
                                                transition: all 0.2s ease;
                                            ">

                                            <i class="ph-bold ph-buildings"
                                            style="font-size:15px; color:#0070C0;"></i>

                                            {{ $p->faculty->name ?? '-' }}

                                        </span>

                                    </td>

                                    <td class="fw-bold">

                                        {{ $p->name }}

                                    </td>

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-2">

                                            <!-- EDIT -->
                                            <button
                                                wire:click="edit('{{ $p->id }}')"
                                                class="btn btn-sm btn-outline-primary">

                                                <i class="ph-bold ph-pencil-simple"></i>

                                            </button>

                                            <!-- DELETE -->
                                            <button
                                                type="button"
                                                onclick="confirmDelete('{{ $p->id }}')"
                                                class="btn btn-sm btn-outline-danger">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4"
                                        class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-folder-open fs-1 d-block mb-2 opacity-25"></i>

                                        Belum ada data program studi.

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

                <div class="card-body">

                    <form wire:submit.prevent="{{ $isEdit ? 'confirmUpdate' : 'store' }}">

                        <!-- FAKULTAS -->
                        <div class="mb-3">

                            <label class="form-label">

                                Fakultas

                            </label>

                            <select
                                wire:model="faculty_id"
                                class="form-select @error('faculty_id') is-invalid @enderror">

                                <option value="">
                                    Pilih Fakultas
                                </option>

                                @foreach($faculties as $f)

                                    <option value="{{ $f->id }}">

                                        {{ $f->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('faculty_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">

                            <label class="form-label">

                                Nama Program Studi

                            </label>

                            <input
                                type="text"
                                wire:model="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama program studi">

                            @error('name')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>

                        <!-- BUTTON -->
                        <div class="text-end">

                            <button class="btn btn-primary">

                                <i class="ph-bold ph-floppy-disk me-1"></i>

                                {{ $isEdit ? 'Update' : 'Simpan' }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>

    <!-- SWEET ALERT -->
@script
<script>

    // ================= DELETE =================
    window.confirmDelete = (id) => {

        Swal.fire({

            title: 'Hapus data?',

            html: `<div>Data yang dihapus tidak dapat dikembalikan.</div>`,

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',

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
            cancelButtonColor: '#6b7280',

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

            title: data.title ?? 'Informasi',

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



