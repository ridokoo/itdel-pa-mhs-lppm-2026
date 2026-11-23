<div>

    <style>
        .page-header {
            background-color: #ffffff !important;
        }

        .page-title {
            color: #1a1a1a !important;
        }

        .card {
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04) !important;
            border-radius: 12px !important;
        }

        .card-body {
            background-color: #ffffff !important;
        }

        .form-control,
        .form-select {
            background-color: #ffffff !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
            border-radius: 8px !important;
            font-size: 14px !important;
        }

        .form-control::placeholder,
        textarea::placeholder {
            color: #9ca3af !important;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff !important;
            color: #374151 !important;
            border-color: #0070C0 !important;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important;
        }

        .form-label {
            color: #374151 !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
        }

        .btn-primary {
            background-color: #0070C0 !important;
            border-color: #0070C0 !important;
            color: #ffffff !important;
            border-radius: 8px !important;
        }

        .btn-primary:hover {
            background-color: #1bad82 !important;
        }

        .btn-success {
            background-color: #0070C0 !important;
            border-color: #0070C0 !important;
            border-radius: 8px !important;
        }

        .btn-light {
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            color: #374151 !important;
            border-radius: 8px !important;
        }

        .table thead th {
            background-color: #f8f9fa !important;
            color: #444 !important;
            border-bottom: 2px solid #eee !important;
            font-weight: 700 !important;
        }

        .table tbody tr {
            background-color: #ffffff !important;
            color: #374151 !important;
        }

        .table tbody tr:hover {
            background-color: #f0fdf4 !important;
        }

        /* SWEET ALERT */
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

        .swal2-cancel{
            border-radius:10px !important;
            padding:10px 24px !important;
            font-weight:600 !important;
        }

        .swal2-confirm:focus,
        .swal2-cancel:focus{
            box-shadow:none !important;
        }

        .swal2-icon.swal2-success{
            border-color:#0070C0 !important;
            color:#0070C0 !important;
        }

        .swal2-icon.swal2-error{
            border-color:#ef4444 !important;
            color:#ef4444 !important;
        }
    </style>

    {{-- HEADER --}}
    <div class="page-header border-bottom mb-4 bg-white">

        <div class="page-header-content d-lg-flex align-items-center">

            <div class="d-flex py-2">

                <h4 class="page-title mb-0 text-dark fw-bold">

                    <i class="ph-bold ph-folders me-2"
                       style="color:#0070C0;"></i>

                    @if($showForm && $isEdit)
                        Edit Kategori Target Luaran
                    @elseif($showForm)
                        Tambah Kategori Target Luaran
                    @else
                        Kelola Kategori Target Luaran
                    @endif

                </h4>

            </div>

            <div class="ms-lg-auto">

                @if(!$showForm)

                    <button wire:click="create"
                            class="btn btn-primary shadow-sm px-3">

                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Kategori Target Luaran

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

                    <table class="table table-hover bg-white mb-0">

                        <thead>
                            <tr>

                                <th class="text-center" style="width:60px;">
                                    No
                                </th>

                                <th class="text-center">
                                    Nama Kategori Target Luaran
                                </th>

                                <th class="text-center" style="width:120px;">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse($categories as $cat)

                                <tr class="align-middle"
                                    wire:key="category-{{ $cat->id }}">

                                    <td class="text-center text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="fw-bold text-dark">
                                        {{ $cat->name }}
                                    </td>

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">

                                            <button wire:click="edit('{{ $cat->id }}')"
                                                    class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent"
                                                    title="Edit">

                                                <i class="ph-bold ph-pencil-line"></i>

                                            </button>

                                            <button onclick="confirmDelete('{{ $cat->id }}')"
                                                    class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent"
                                                    title="Hapus">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-folder-open fs-2 d-block mb-2 opacity-25"></i>

                                        Belum ada data kategori.

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

                        {{-- TYPE --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Tipe Target Luaran
                            </label>

                            <select wire:model="output_target_type_id"
                                    class="form-control @error('output_target_type_id') is-invalid @enderror">

                                <option value="">
                                    -- Pilih Tipe --
                                </option>

                                @foreach($targetTypes as $type)

                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('output_target_type_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- NAME --}}
                        <div class="mb-4">

                            <label class="form-label">
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

                        {{-- BUTTON --}}
                        <div class="border-top pt-3 mt-2 d-flex justify-content-end gap-2">

                            <button type="button"
                                    wire:click="cancel"
                                    class="btn btn-link text-muted fw-bold text-decoration-none">

                                Cancel

                            </button>

                            <button type="submit"
                                    class="btn btn-primary px-4 shadow-sm"
                                    wire:loading.attr="disabled"
                                    wire:target="store, update">

                                <span wire:loading.remove
                                      wire:target="store, update">

                                    <i class="ph-bold ph-floppy-disk me-2"></i>

                                    {{ $isEdit ? 'Update' : 'Simpan Data' }}

                                </span>

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
    Livewire.on('showSuccessAlert', (event) => {

        Swal.fire({

            title: 'Berhasil!',

            html: `<div>${event.message}</div>`,

            icon: 'success',

            confirmButtonText: 'OK',

            confirmButtonColor: '#0070C0',

            timer: 2500,
            timerProgressBar: true

        });

    });


    // ================= ERROR =================
    Livewire.on('showErrorAlert', (event) => {

        Swal.fire({

            title: 'Gagal!',

            html: `<div>${event.message}</div>`,

            icon: 'error',

            confirmButtonText: 'OK',

            confirmButtonColor: '#ef4444'

        });

    });

</script>
@endscript

</div>



