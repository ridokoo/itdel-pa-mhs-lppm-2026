<div>
    <style>
        body, .content, .container-fluid {
            background-color: #f8fafc !important;
        }
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
        .card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 14px rgba(0,0,0,0.04) !important;
            overflow: hidden !important;
        }
        .table thead th {
            background-color: #f9fafb !important;
            color: #374151 !important;
            font-weight: 700 !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 14px 16px !important;
            font-size: 13px !important;
        }
        .table tbody tr:hover {
            background-color: #eff6ff !important;
        }
        .form-label {
            color: #374151 !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
        }
        .form-control {
            border-radius: 10px !important;
            border: 1px solid #d1d5db !important;
        }
        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
        }
    </style>

    {{-- ================= HEADER ================= --}}
    <div class="page-header border-0 mb-4">
        <div class="page-header-content d-lg-flex align-items-center">
            <div class="d-flex py-2">
                <h4 class="page-title mb-0 fw-bold">
                    <i class="ph-bold ph-seal-check me-2 text-primary"></i>
                    @if($showForm && $isEdit)
                        Edit Status Usulan Final
                    @elseif($showForm)
                        Tambah Status Usulan Final
                    @else
                        Kelola Status Usulan Final
                    @endif
                </h4>
            </div>

            <div class="ms-lg-auto">
                @if(!$showForm)
                    <button wire:click="create" class="btn btn-primary shadow-sm px-3">
                        <i class="ph-bold ph-plus-circle me-2"></i> Tambah Status Usulan Final
                    </button>
                @else
                    <button wire:click="cancel" class="btn btn-light px-3">
                        <i class="ph-bold ph-arrow-left me-2"></i> Kembali
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= ALERT SUCCESS ================= --}}
    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div class="alert alert-dismissible fade show text-white shadow rounded-3"
                style="background-color: #28a745; border: none;" role="alert">
                <i class="ph ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div class="alert alert-dismissible fade show text-white shadow rounded-3"
                style="background-color: #dc3545; border: none;" role="alert">
                <i class="ph ph-warning-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div class="content">

        {{-- ================= FORM SECTION ================= --}}
        @if($showForm)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nama Status Usulan Final</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Diterima, Diterima dengan Revisi, Ditolak">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-4 d-flex justify-content-end gap-2">
                            <button type="button" wire:click="cancel" class="btn btn-light">Batal</button>
                            @if($isEdit)
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="ph-bold ph-pencil-simple me-2"></i> Update
                                </button>
                            @else
                                <button type="submit" class="btn btn-success px-4 shadow-sm">
                                    <i class="ph-bold ph-floppy-disk me-2"></i> Simpan Data
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ================= DELETE CONFIRMATION ================= --}}
        @if($showDeleteConfirm)
            <div class="alert border-0 shadow-sm mb-4"
                style="background-color:#fff5f5; border-left:4px solid #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="text-danger fw-semibold">
                        <i class="ph-bold ph-warning-circle me-2"></i>
                        Yakin ingin menghapus status usulan final ini?
                    </div>
                    <div class="d-flex gap-2">
                        <button wire:click="destroy" class="btn btn-danger btn-sm">Ya, Hapus</button>
                        <button wire:click="cancel" class="btn btn-light btn-sm">Batal</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= TABLE SECTION ================= --}}
        @if(!$showForm)
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:70px;">No</th>
                                <th class="text-center">Nama Status Usulan Final</th>
                                <th class="text-center" style="width:120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($statuses as $s)
                                <tr wire:key="sfu-{{ $s->id }}"
                                    class="{{ $showDeleteConfirm && $selected_id == $s->id ? 'table-danger' : '' }}">
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        @php
                                            $sName = strtolower($s->name);
                                            $badgeClass = match(true) {
                                                str_contains($sName, 'terima') && !str_contains($sName, 'revisi') => 'bg-success',
                                                str_contains($sName, 'revisi')                                     => 'bg-warning text-dark',
                                                str_contains($sName, 'tolak')                                      => 'bg-danger',
                                                default                                                             => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} fw-semibold px-3 py-2"
                                            style="font-size:12px; letter-spacing:.3px;">
                                            {{ strtoupper($s->name) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button wire:click="edit({{ $s->id }})"
                                                class="btn btn-light btn-sm text-primary shadow-sm">
                                                <i class="ph-bold ph-pencil-line"></i>
                                            </button>
                                            <button wire:click="deleteConfirm({{ $s->id }})"
                                                class="btn btn-light btn-sm text-danger shadow-sm">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="ph-bold ph-folder-open fs-2 d-block mb-2 opacity-25"></i>
                                        Belum ada data status usulan final.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>



