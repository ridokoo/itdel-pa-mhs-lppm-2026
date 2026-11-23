<div class="container-fluid px-0">

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
        textarea:focus {
            border-color: #0070C0 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important;
            color: #111827 !important;
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

</div>

    @script
    <script>

        Livewire.on('showSuccessAlert', (data) => {

            Swal.fire({
                icon: data[0].icon,
                title: data[0].title,
                text: data[0].text,
                timer: 2500,
                showConfirmButton: false,
                confirmButtonColor: '#0070C0'
            });

        });

        Livewire.on('showErrorAlert', (data) => {

            Swal.fire({
                icon: data[0].icon,
                title: data[0].title,
                text: data[0].text,
                confirmButtonColor: '#ef4444'
            });

        });

    </script>
    @endscript

    {{-- HEADER --}}
    <div class="page-header border-0 mb-3">

        <div class="page-header-content d-sm-flex align-items-sm-center">

            <h4 class="page-title mb-0 fw-bold">

                @if($showForm && $isEdit)
                    Edit Kategori Biaya
                @elseif($showForm)
                    Tambah Kategori Biaya
                @else
                    Kelola Kategori Biaya (RAB)
                @endif

            </h4>

            <div class="ms-sm-auto mt-3 mt-sm-0">

                @if(!$showForm && !$showDeleteConfirm)

                    <button type="button"
                            class="btn btn-primary"
                            wire:click="create">

                        <i class="ph ph-coins me-2"></i>
                        Tambah Kategori Biaya

                    </button>

                @endif

            </div>

        </div>

    </div>


    {{-- FORM --}}
    @if($showForm)

        <div class="card border-0 shadow-sm mb-4 w-100">

            <div class="card-body">

                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                    <div class="row g-3">

                        {{-- NAMA --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Nama Kategori
                            </label>

                            <input type="text"
                                   wire:model="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Masukkan nama kategori">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ORDER --}}
                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Order
                            </label>

                            <input type="number"
                                   wire:model="display_order"
                                   class="form-control @error('display_order') is-invalid @enderror">

                            @error('display_order')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- MIN --}}
                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Min %
                            </label>

                            <input type="number"
                                   wire:model="percentage_min"
                                   class="form-control @error('percentage_min') is-invalid @enderror">

                            @error('percentage_min')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- MAX --}}
                        <div class="col-md-2">

                            <label class="form-label fw-semibold">
                                Max %
                            </label>

                            <input type="number"
                                   wire:model="percentage_max"
                                   class="form-control @error('percentage_max') is-invalid @enderror">

                            @error('percentage_max')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea wire:model="description"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Masukkan deskripsi kategori biaya"></textarea>

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <button type="button"
                                class="btn btn-light"
                                wire:click="cancel">

                            <i class="ph ph-x me-1"></i>
                            Batal

                        </button>

                        @if($isEdit)

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="ph ph-pencil-simple me-1"></i>
                                Update

                            </button>

                        @else

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="ph ph-floppy-disk me-1"></i>
                                Simpan Data

                            </button>

                        @endif

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- DELETE CONFIRM --}}
    @if($showDeleteConfirm)

        <div class="alert border-0 shadow-sm mb-4"
             style="background-color:#fff5f5;border-left:4px solid #dc3545 !important;">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                <div class="text-danger fw-semibold">

                    <i class="ph ph-warning me-2"></i>
                    Yakin ingin menghapus kategori biaya ini?

                </div>

                <div class="d-flex gap-2">

                    <button wire:click="destroy"
                            class="btn btn-danger btn-sm">

                        <i class="ph ph-trash me-1"></i>
                        Ya, Hapus

                    </button>

                    <button wire:click="cancel"
                            class="btn btn-light btn-sm">

                        Batal

                    </button>

                </div>

            </div>

        </div>

    @endif


    {{-- TABLE --}}
    @if(!$showForm)

        <div class="card border-0 shadow-sm w-100">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 70px;">Order</th>
                            <th>Kategori Biaya</th>
                            <th class="text-center">Min %</th>
                            <th class="text-center">Max %</th>
                            <th>Dibuat Oleh</th>

                            <th class="text-center"
                                style="width:120px;">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($categories as $c)

                            <tr>

                                <td>

                                    <span class="badge bg-light text-dark fw-bold">
                                        {{ $c->display_order }}
                                    </span>

                                </td>

                                <td>

                                    <div class="fw-bold text-primary">
                                        {{ $c->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $c->description }}
                                    </small>

                                </td>

                                <td class="text-center">
                                    {{ $c->percentage_min }}%
                                </td>

                                <td class="text-center">
                                    {{ $c->percentage_max }}%
                                </td>

                                <td>

                                    <small>
                                        {{ $c->creator->name ?? 'System' }}
                                    </small>

                                </td>

                                <td class="text-center">

                                    <div class="d-inline-flex gap-1">

                                        <button wire:click="edit({{ $c->id }})"
                                                class="btn btn-sm btn-light text-primary">

                                            <i class="ph ph-pencil-simple"></i>

                                        </button>

                                        <button wire:click="deleteConfirm({{ $c->id }})"
                                                class="btn btn-sm btn-light text-danger">

                                            <i class="ph ph-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5 text-muted">

                                    <i class="ph ph-folder-open d-block fs-2 mb-2 opacity-25"></i>

                                    Belum ada kategori biaya.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>



