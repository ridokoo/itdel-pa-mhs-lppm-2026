<div>
    <!-- STYLE INTERNAL UNTUK TEMA PUTIH & HIJAU & FIX SWEETALERT -->
    <style>
        .content, .page-header, .card { background-color: #ffffff !important; color: #333333 !important; }
        .btn-primary { background-color: #0070C0 !important; border-color: #0070C0 !important; color: #ffffff !important; }
        .btn-primary:hover { background-color: #1bad82 !important; }
        .form-control, .form-select { background-color: #ffffff !important; border: 1px solid #e0e0e0 !important; color: #333 !important; }
        .form-control:focus, .form-select:focus { border-color: #0070C0 !important; box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important; }
        .table thead th { background-color: #f8f9fa !important; color: #444 !important; border-bottom: 2px solid #eee !important; font-weight: 700; text-align: center; }
        .page-title i { color: #0070C0 !important; }
        .card { border: 1px solid #f0f0f0 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important; border-radius: 12px !important; }
        .alert-info { background-color: #e7f3ef !important; color: #1a5c44 !important; border: none !important; }

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
                    <i class="ph-bold ph-folders me-2"></i>
                    @if($viewState == 'index') Manajemen Kategori Dokumen
                    @elseif($viewState == 'create') Tambah Kategori Baru
                    @else Edit Kategori @endif
                </h4>
            </div>
            <div class="collapse d-lg-block my-lg-auto ms-lg-auto">
                <div class="d-flex justify-content-center">
                    @if($viewState == 'index')
                        <button type="button" wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                            <i class="ph-bold ph-plus-circle me-2"></i> Tambah Kategori
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
            <!-- TABEL DAFTAR KATEGORI -->
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr>
                                <th>Nama Kategori Dokumen</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $item)
                            <tr class="align-middle" wire:key="cat-{{ $item->id }}">
                                <td class="fw-bold text-dark">{{ $item->name }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <button wire:click="showEdit({{ $item->id }})" class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent me-2" title="Edit">
                                            <i class="ph-bold ph-pencil-line"></i>
                                        </button>
                                        {{-- MODIFIKASI: Menggunakan confirmDelete --}}
                                        <button type="button" onclick="confirmDelete({{ $item->id }})" class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent" title="Hapus">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-5 text-muted">
                                    <i class="ph-bold ph-folder-open fs-2 d-block mb-2 opacity-25"></i>
                                    Belum ada kategori dokumen yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- FORM INPUT KATEGORI -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $viewState == 'create' ? 'store' : 'confirmUpdate' }}">
                        <div class="row">
                            <!-- Sisi Kiri (Konten Utama) -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Nama Kategori</label>
                                    <!-- HAPUS .defer agar data terbaca oleh PHP -->
                                    <input type="text" class="form-control" wire:model="name" placeholder="Contoh: Template Proposal, Laporan Akhir, dll">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="alert alert-info border-0 shadow-none mb-0 d-flex align-items-center">
                                    <i class="ph-bold ph-info fs-4 me-2"></i>
                                    <span>Nama kategori ini akan muncul sebagai opsi pilihan saat Anda mengunggah dokumen baru.</span>
                                </div>
                            </div>

                            <!-- Sisi Kanan (Status/Info) -->
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded border">
                                    <div class="mb-2">
                                        <label class="form-label fw-bold text-dark">Status Kategori</label>
                                        <select class="form-select" disabled>
                                            <option value="1">Aktif</option>
                                        </select>
                                    </div>
                                    <small class="text-muted d-block italic">
                                        <i class="ph-bold ph-check-circle me-1"></i> Kategori otomatis aktif setelah disimpan.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Bawah Form -->
                        <div class="border-top pt-3 mt-4 text-end">
                            <button type="button" wire:click="showIndex" class="btn btn-link text-muted fw-bold">Cancel</button>

                            <!-- Tambahkan wire:target agar tombol menunggu proses selesai -->
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                wire:loading.attr="disabled"
                                wire:target="store, update">
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
                text: "Perubahan data akan disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0070C0',
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.update();
                }
            })
        });

        window.addEventListener('swal:modal', event => {
            let data = event.detail[0] ? event.detail[0] : event.detail;
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



