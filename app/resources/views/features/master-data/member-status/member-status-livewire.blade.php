<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="page-header border-0 mb-3">
        <div class="page-header-content d-sm-flex align-items-sm-center">

            <h4 class="page-title mb-0 fw-bold">
                @if($showForm && $isEdit)
                    Edit Status Anggota Usulan
                @elseif($showForm)
                    Tambah Status Anggota Usulan
                @else
                    Kelola Status Anggota Usulan
                @endif
            </h4>

            <div class="ms-sm-auto mt-3 mt-sm-0">

                @if(!$showForm && !$showDeleteConfirm)
                    <button type="button"
                            class="btn btn-primary"
                            wire:click="create">
                        <i class="ph ph-plus-circle me-2"></i>
                        Tambah Status Anggota Usulan
                    </button>
                @endif

            </div>

        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div class="alert alert-dismissible fade show text-white shadow rounded-3"
                 style="background-color: #28a745; border: none;"
                 role="alert">

                <i class="ph ph-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if (session()->has('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3">
            <i class="ph ph-warning-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    @if($showForm)

        <div class="card border-0 shadow-sm mb-4 w-100">
            <div class="card-body">

                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                    <div class="row g-3">

                        {{-- NAMA STATUS --}}
                        <div class="col-md-12">

                            <label class="form-label fw-semibold">
                                Nama Status Anggota Usulan
                            </label>

                            <input type="text"
                                   wire:model="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Masukkan nama status anggota">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

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

    {{-- ================= DELETE CONFIRM ================= --}}
    @if($showDeleteConfirm)

        <div class="alert border-0 shadow-sm mb-4"
             style="background-color: #fff5f5; border-left: 4px solid #dc3545 !important;">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                <div class="text-danger fw-semibold">
                    <i class="ph ph-warning me-2"></i>
                    Yakin ingin menghapus data ini? Tindakan ini tidak bisa dibatalkan.
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

    {{-- ================= TABLE ================= --}}
    @if(!$showForm)

        <div class="card border-0 shadow-sm w-100">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 w-100">

                    <thead>
                        <tr class="table-light">

                            <th class="text-center" style="width: 60px;">
                                No
                            </th>

                            <th class="text-center">
                                Nama Status Anggota Usulan
                            </th>

                            <th class="text-center" style="width:120px;">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($statuses as $s)

                            <tr class="{{ $showDeleteConfirm && $selected_id == $s->id ? 'table-danger' : '' }}">

                                <td class="text-muted">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-bold text-dark">
                                    {{ $s->name }}
                                </td>

                                <td class="text-center">

                                    <div class="d-inline-flex gap-1">

                                        {{-- EDIT --}}
                                        <button wire:click="edit({{ $s->id }})"
                                                class="btn btn-sm btn-light text-primary">
                                            <i class="ph ph-pencil-simple"></i>
                                        </button>

                                        {{-- DELETE --}}
                                        <button wire:click="deleteConfirm({{ $s->id }})"
                                                class="btn btn-sm btn-light text-danger">
                                            <i class="ph ph-trash"></i>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3"
                                    class="text-center py-4 text-muted">

                                    <i class="ph ph-folder-open d-block fs-2 mb-2 opacity-25"></i>

                                    Belum ada data status anggota.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>



