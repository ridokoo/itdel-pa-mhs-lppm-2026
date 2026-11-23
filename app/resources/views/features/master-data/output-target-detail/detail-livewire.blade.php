<div>

    <style>
    /* ================= GLOBAL ================= */
    body,
    .content,
    .container-fluid {
        background-color: #f8fafc !important;
    }

    /* ================= PAGE HEADER ================= */
    .page-header {
        background-color: #ffffff !important;
        border-radius: 14px !important;
        padding: 1rem 1.2rem !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04) !important;
    }

    .page-title {
        color: #111827 !important;
        font-weight: 700 !important;
    }

    /* ================= CARD ================= */
    .card {
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04) !important;
        overflow: hidden !important;
    }

    .card-body {
        background-color: #ffffff !important;
    }

    /* ================= TABLE ================= */
    .table {
        margin-bottom: 0 !important;
        background-color: #ffffff !important;
    }

    .table thead {
        background-color: #f9fafb !important;
    }

    .table thead th {
        background-color: #f9fafb !important;
        color: #374151 !important;
        font-weight: 700 !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 14px 16px !important;
        font-size: 13px !important;
        white-space: nowrap;
    }

    .table tbody tr {
        background-color: #ffffff !important;
        transition: 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f0fdf4 !important;
    }

    .table tbody td {
        color: #374151 !important;
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-color: #f1f5f9 !important;
    }

    /* ================= FORM ================= */
    .form-label {
        color: #374151 !important;
        font-weight: 600 !important;
        margin-bottom: 6px !important;
        font-size: 13.5px !important;
    }

    .form-control,
    .form-select,
    textarea {
        background-color: #ffffff !important;
        border: 1px solid #d1d5db !important;
        border-radius: 10px !important;
        color: #374151 !important;
        min-height: 44px !important;
        font-size: 14px !important;
        box-shadow: none !important;
    }

    .form-control::placeholder,
    textarea::placeholder {
        color: #9ca3af !important;
    }

    .form-control:focus,
    .form-select:focus,
    textarea:focus {
        border-color: #0070C0 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important;
        color: #111827 !important;
    }

    .form-check-input {
        width: 2.8rem !important;
        height: 1.4rem !important;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #0070C0 !important;
        border-color: #0070C0 !important;
    }

    /* ================= BUTTON ================= */
    .btn {
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 10px 18px !important;
        transition: all .2s ease !important;
    }

    .btn-primary {
        background-color: #0070C0 !important;
        border-color: #0070C0 !important;
        color: #ffffff !important;
    }

    .btn-primary:hover {
        background-color: #18b589 !important;
        border-color: #18b589 !important;
    }

    .btn-success {
        background-color: #0070C0 !important;
        border-color: #0070C0 !important;
    }

    .btn-success:hover {
        background-color: #18b589 !important;
        border-color: #18b589 !important;
    }

    .btn-light {
        background-color: #ffffff !important;
        border: 1px solid #d1d5db !important;
        color: #374151 !important;
    }

    .btn-light:hover {
        background-color: #f9fafb !important;
    }

    .btn-outline-primary {
        border-color: #0070C0 !important;
        color: #0070C0 !important;
    }

    .btn-outline-primary:hover {
        background-color: #0070C0 !important;
        color: #ffffff !important;
    }

    .btn-outline-danger:hover {
        color: #ffffff !important;
    }

    /* ================= BADGE ================= */
    .badge {
        border-radius: 999px !important;
        padding: 7px 12px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }

    /* ================= ALERT ================= */
    .alert {
        border-radius: 12px !important;
    }

    /* ================= INVALID ================= */
    .invalid-feedback {
        font-size: 12px !important;
    }

    /* ================= SWEET ALERT ================= */
    .swal2-popup {
        width: 22rem !important;
        padding: 1.8rem !important;
        border-radius: 20px !important;
        background: #ffffff !important;
        color: #333333 !important;
    }

    .swal2-title {
        font-size: 1.6rem !important;
        font-weight: 700 !important;
        color: #111827 !important;
    }

    .swal2-html-container {
        color: #6b7280 !important;
        font-size: 14px !important;
        line-height: 1.6 !important;
    }

    .swal2-confirm {
        background: #0070C0 !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 10px 22px !important;
        font-weight: 600 !important;
        box-shadow: none !important;
    }

    .swal2-cancel {
        border-radius: 10px !important;
        padding: 10px 22px !important;
        font-weight: 600 !important;
    }

    .swal2-confirm:focus,
    .swal2-cancel:focus {
        box-shadow: none !important;
    }

    .swal2-icon {
        width: 85px !important;
        height: 85px !important;
        border-width: 4px !important;
    }

    .swal2-success-ring {
        border: 4px solid rgba(32, 201, 151, .20) !important;
    }

    .swal2-icon.swal2-success {
        border-color: #0070C0 !important;
        color: #0070C0 !important;
    }

    .swal2-icon.swal2-error {
        border-color: #ef4444 !important;
        color: #ef4444 !important;
    }

    .swal2-icon.swal2-warning {
        border-color: #f59e0b !important;
        color: #f59e0b !important;
    }

    .swal2-timer-progress-bar {
        background: #0070C0 !important;
    }
</style>
    {{-- HEADER --}}
    <div class="page-header border-bottom mb-4 bg-white">

        <div class="page-header-content d-lg-flex align-items-center">

            <div class="d-flex py-2">

                <h4 class="page-title mb-0 fw-bold">

                    <i class="ph-bold ph-list-checks me-2 text-success"></i>

                    @if($showForm && $isEdit)
                        Edit Detail Target Luaran
                    @elseif($showForm)
                        Tambah Detail Target Luaran
                    @else
                        Kelola Detail Target Luaran
                    @endif

                </h4>

            </div>

            <div class="ms-lg-auto">

                @if(!$showForm)

                    <button wire:click="create"
                            class="btn btn-primary shadow-sm px-3">

                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Detail Target Luaran

                    </button>

                @else

                    <button wire:click="cancel"
                            class="btn btn-light border px-3">

                        <i class="ph-bold ph-arrow-left me-2"></i>
                        Kembali

                    </button>

                @endif

            </div>

        </div>

    </div>


    {{-- CONTENT --}}
    <div class="content">

        {{-- ================= TABLE ================= --}}
        @if(!$showForm)

            <div class="card border-0 shadow-sm">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>
                            <tr>
                                <th style="width:60px;" class="text-center">
                                    No
                                </th>

                                <th class="text-center">
                                    Kategori & Jenis
                                </th>

                                <th class="text-center">
                                    Tingkatan / Detail Target Luaran
                                </th>

                                <th class="text-center" style="width:120px;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($details as $d)

                                <tr wire:key="detail-{{ $d->id }}">

                                    <td class="text-center text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <div class="d-flex flex-wrap align-items-center gap-2">

                                            {{-- KATEGORI --}}
                                            <span
                                                style="
                                                    background: linear-gradient(135deg, #eaf4ff, #dceeff);
                                                    color: #0b5ea8;
                                                    border: 1px solid #b8d9f5;
                                                    border-radius: 10px;
                                                    padding: 7px 14px;
                                                    font-size: 12px;
                                                    font-weight: 700;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 6px;
                                                    box-shadow: 0 2px 6px rgba(0, 112, 192, 0.08);
                                                ">

                                                <i class="ph-bold ph-folders"></i>

                                                {{ $d->category->name ?? '-' }}

                                            </span>

                                            {{-- JENIS --}}
                                            <span
                                                style="
                                                    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
                                                    color: #047857;
                                                    border: 1px solid #a7f3d0;
                                                    border-radius: 10px;
                                                    padding: 7px 14px;
                                                    font-size: 12px;
                                                    font-weight: 700;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 6px;
                                                    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.08);
                                                ">

                                                <i class="ph-bold ph-shapes"></i>

                                                {{ $d->category->type->name ?? '-' }}

                                            </span>

                                        </div>

                                    </td>

                                    <td class="fw-semibold text-primary">
                                        {{ $d->name }}
                                    </td>

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">

                                            <button wire:click="edit('{{ $d->id }}')"
                                                    class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent">

                                                <i class="ph-bold ph-pencil-line"></i>

                                            </button>

                                            <button onclick="confirmDelete('{{ $d->id }}')"
                                                    class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4"
                                        class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-folder-open fs-2 d-block mb-2 opacity-25"></i>

                                        Belum ada data detail target luaran.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        {{-- ================= FORM ================= --}}
        @else

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                        <div class="row g-3">

                            {{-- KATEGORI --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Kategori Target Luaran
                                </label>

                                <select wire:model="output_target_category_id"
                                        class="form-control @error('output_target_category_id') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih Kategori --
                                    </option>

                                    @foreach($categories as $c)

                                        <option value="{{ $c->id }}">
                                            {{ $c->name }}
                                            ({{ $c->type->name ?? '-' }})
                                        </option>

                                    @endforeach

                                </select>

                                @error('output_target_category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- DETAIL --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Tingkatan / Detail Luaran
                                </label>

                                <input type="text"
                                       wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Masukkan detail luaran">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <div class="border-top pt-3 mt-4 d-flex justify-content-end gap-2">

                            <button type="button"
                                    wire:click="cancel"
                                    class="btn btn-link text-muted fw-bold text-decoration-none">

                                Cancel

                            </button>

                            <button type="submit"
                                    class="btn btn-primary px-4 shadow-sm">

                                <i class="ph-bold ph-floppy-disk me-2"></i>

                                {{ $isEdit ? 'Update' : 'Simpan Data' }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>


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

                $wire.deleteConfirm(id);
                $wire.destroy();

            }

        });

    };


    // ================= SUCCESS =================
    Livewire.on('showSuccessAlert', (data) => {

        Swal.fire({

            title: data[0].title,

            html: `<div>${data[0].text}</div>`,

            icon: data[0].icon,

            confirmButtonColor: '#0070C0',

            timer: 2500,
            timerProgressBar: true

        });

    });


    // ================= ERROR =================
    Livewire.on('showErrorAlert', (data) => {

        Swal.fire({

            title: data[0].title,

            html: `<div>${data[0].text}</div>`,

            icon: data[0].icon,

            confirmButtonColor: '#ef4444'

        });

    });

</script>
@endscript

</div>



