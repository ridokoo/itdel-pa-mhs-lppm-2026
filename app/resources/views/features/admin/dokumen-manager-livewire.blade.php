<div>
    <!-- STYLE INTERNAL UNTUK TEMA PUTIH & HIJAU & FIX SWEETALERT -->
    <style>
/* =========================
   BADGE KATEGORI DOKUMEN
   ========================= */

.category-badge {
    background: linear-gradient(135deg, #eaf4ff, #dceeff) !important;
    color: #0b5ea8 !important;
    border: 1px solid #b8d9f5 !important;
    border-radius: 10px !important;
    padding: 7px 14px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0, 112, 192, 0.08);
    transition: all 0.2s ease;
}

/* Hover effect */
.category-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0, 112, 192, 0.15);
}

        .content, .page-header, .card { background-color: #ffffff !important; color: #333333 !important; }
        .btn-primary { background-color: #0070C0 !important; border-color: #0070C0 !important; color: #ffffff !important; }
        .btn-primary:hover { background-color: #1bad82 !important; }
        .form-control, .form-select { background-color: #ffffff !important; border: 1px solid #e0e0e0 !important; color: #333 !important; }
        .form-control:focus, .form-select:focus { border-color: #0070C0 !important; box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important; }
        .table thead th { background-color: #f8f9fa !important; color: #444 !important; border-bottom: 2px solid #eee !important; font-weight: 700; text-align: center; }
        .page-title i { color: #0070C0 !important; }
        .card { border: 1px solid #f0f0f0 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important; border-radius: 12px !important; }

        /* ULTIMATE FIX IKON SWEETALERT */
        .swal2-icon { width: 80px !important; height: 80px !important; min-width: 80px !important; min-height: 80px !important; border-width: 4px !important; margin: 20px auto 10px !important; display: flex !important; align-items: center !important; justify-content: center !important; box-sizing: content-box !important; }
        .swal2-icon .swal2-icon-content { font-size: 50px !important; display: flex !important; align-items: center !important; }
        .swal2-success-ring { width: 80px !important; height: 80px !important; box-sizing: content-box !important; }
        .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: #0070C0 !important; height: 5px !important; }
        .swal2-title { font-size: 1.4rem !important; padding-top: 10px !important; font-weight: 700 !important; color: #333 !important; }
        .swal2-popup { border-radius: 15px !important; padding: 1.5rem !important; width: 22rem !important; }
    </style>

    <!-- HEADER HALAMAN -->
    <div class="page-header border-bottom mb-3 bg-white">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex py-2">
                <h4 class="page-title mb-0 text-dark fw-bold">
                    <i class="ph-bold ph-file-text me-2"></i>
                    @if($viewState == 'index') Manajemen Dokumen
                    @elseif($viewState == 'create') Tambah Dokumen Baru
                    @else Edit Dokumen @endif
                </h4>
            </div>
            <div class="collapse d-lg-block my-lg-auto ms-lg-auto">
                <div class="d-flex justify-content-center">
                    @if($viewState == 'index')
                        <button type="button" wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                            <i class="ph-bold ph-plus-circle me-2"></i> Tambah Dokumen
                        </button>
                    @else
                        <button type="button" wire:click="showIndex" class="btn btn-light border px-3">
                            <i class="ph-bold ph-arrow-left me-2"></i> Kembali
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- AREA KONTEN -->
    <div class="content">
        @if($viewState == 'index')
            <!-- TABEL DAFTAR DOKUMEN -->
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr>
                                <th>Nama Dokumen</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $item)
                            <tr class="align-middle" wire:key="doc-{{ $item->id }}">
                                <td class="fw-bold text-dark">{{ $item->name }}</td>
                                <td class="text-center">
                                    <span class="category-badge">
                                        {{ $item->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->status == 'published' ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <button wire:click="showEdit({{ $item->id }})" class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent me-2">
                                            <i class="ph-bold ph-pencil-line"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete({{ $item->id }})" class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada dokumen.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- FORM INPUT DOKUMEN -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    {{-- PENTING: Menggunakan wire:submit agar tombol langsung bisa diklik --}}
                    <form wire:submit.prevent="{{ $viewState == 'create' ? 'store' : 'confirmUpdate' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Nama Dokumen</label>
                                    <input type="text" class="form-control shadow-sm" wire:model="name" placeholder="Judul dokumen...">
                                    @error('name') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Deskripsi</label>
                                    <textarea class="form-control shadow-sm" rows="6" wire:model="description" placeholder="Keterangan singkat..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border mb-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Kategori</label>
                                        <select class="form-select shadow-sm" wire:model="category_id">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-bold text-dark">Status</label>
                                        <select class="form-select shadow-sm" wire:model="status">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded border">
                                    <label class="form-label fw-bold text-dark">File Dokumen</label>
                                    <input type="file" class="form-control shadow-sm" wire:model="file">
                                    <div wire:loading wire:target="file" class="text-info fs-xs mt-1">
                                        <i class="ph-bold ph-spinner spinner me-1"></i> Sedang mengunggah file...
                                    </div>
                                    @if($old_file && !$file)
                                        <div class="mt-2 fs-xs text-muted"><i class="ph-bold ph-check-circle text-success"></i> File sudah tersedia.</div>
                                    @endif
                                    @error('file') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-4 text-end">
                            <button type="button" wire:click="showIndex" class="btn btn-link text-muted fw-bold">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                wire:loading.attr="disabled"
                                wire:target="file, store, update">
                                <i class="ph-bold ph-floppy-disk me-2"></i>
                                <span wire:loading.remove wire:target="store, update">
                                    {{ $viewState == 'create' ? 'Simpan Data' : 'Update' }}
                                </span>
                                <span wire:loading wire:target="store, update">Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- JAVASCRIPT UNTUK SWEETALERT -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah data dihapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) { @this.delete(id); } })
        }

        window.addEventListener('triggerUpdateConfirm', () => {
            Swal.fire({
                title: 'Apakah data diubah?',
                text: "Perubahan data akan disimpan",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0070C0',
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) { @this.update(); } })
        });

        window.addEventListener('swal:modal', event => {
            let data = event.detail[0] || event.detail;
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                confirmButtonColor: '#0070C0',
                timer: 3000
            });
        });
    </script>
</div>



