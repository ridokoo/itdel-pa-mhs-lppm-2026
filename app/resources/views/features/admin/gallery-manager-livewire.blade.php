<div>
    <!-- STYLE CUSTOM & FIX SWEETALERT -->
    <style>
        .content, .page-header, .card { background-color: #ffffff !important; color: #333333 !important; }
        .btn-primary, .btn-success { background-color: #0070C0 !important; border-color: #0070C0 !important; color: #ffffff !important; }
        .btn-primary:hover, .btn-success:hover { background-color: #1bad82 !important; }
        .btn-green-soft { background-color: #e8f5e9 !important; color: #0070C0 !important; border: 1px solid #0070C0 !important; font-weight: 600; }
        .page-title i { color: #0070C0 !important; }
        .card { border: 1px solid #f0f0f0 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important; border-radius: 15px !important; }
        .form-control:focus, .form-select:focus { border-color: #0070C0 !important; box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important; }
        .badge-media-count { background-color: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(4px); font-weight: 500; }

        /* FIX IKON SWEETALERT */
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
                    <i class="ph-bold ph-image me-2"></i>
                    @if($viewState == 'index') Manajemen Galeri
                    @elseif($viewState == 'manage_items') Isi Album: {{ $currentGallery->title }}
                    @elseif($viewState == 'create') Tambah Album Baru
                    @else Edit Album @endif
                </h4>
            </div>
            <div class="ms-lg-auto my-lg-auto">
                <div class="d-flex justify-content-center">
                    @if($viewState == 'index')
                        <button wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                            <i class="ph-bold ph-plus-circle me-2"></i> Tambah Album
                        </button>
                    @else
                        <button wire:click="showIndex" class="btn btn-light border px-3">
                            <i class="ph-bold ph-arrow-left me-2"></i> Kembali
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if($viewState == 'index')
            <!-- GRID ALBUM -->
            <div class="row">
                @forelse($galleries as $g)
                <div class="col-sm-6 col-xl-3 mb-4" wire:key="gallery-{{ $g->id }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ asset('storage/'.$g->cover_image) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                            <span class="badge badge-media-count position-absolute bottom-0 end-0 m-2 px-2 py-1 rounded-pill text-white">
                                <i class="ph-bold ph-images me-1"></i> {{ $g->items_count }} Media
                            </span>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $g->title }}</h6>
                            <small class="text-muted d-block mb-3"><i class="ph-bold ph-calendar-blank me-1"></i> {{ \Carbon\Carbon::parse($g->event_date)->format('d M Y') }}</small>
                            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                <button wire:click="showManageItems({{ $g->id }})" class="btn btn-sm btn-green-soft px-3 rounded-pill shadow-sm">Isi Album</button>
                                <div class="d-inline-flex">
                                    <button wire:click="showEdit({{ $g->id }})" class="btn btn-sm btn-outline-primary btn-icon rounded-pill border-transparent me-1"><i class="ph-bold ph-pencil-line"></i></button>
                                    <button type="button" onclick="confirmDelete({{ $g->id }})" class="btn btn-sm btn-outline-danger btn-icon rounded-pill border-transparent"><i class="ph-bold ph-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 text-muted"><p>Belum ada album galeri.</p></div>
                @endforelse
            </div>

        @elseif($viewState == 'manage_items')
            <!-- MANAJEMEN FOTO -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="ph-bold ph-upload-simple me-2 text-success"></i>Unggah Foto Baru</h6>
                    <div class="row align-items-end">
                        <div class="col-md-9">
                            <input type="file" class="form-control" wire:model="photos" multiple>
                            <div wire:loading wire:target="photos" class="text-info fs-xs mt-1">Mengunggah...</div>
                        </div>
                        <div class="col-md-3">
                            <button wire:click="addPhotos" class="btn btn-success w-100 shadow-sm" wire:loading.attr="disabled" wire:target="photos">Simpan Foto</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($currentGallery->items as $item)
                <div class="col-md-3 col-sm-6 mb-4" wire:key="item-{{ $item->id }}">
                    <div class="card border-0 shadow-sm position-relative overflow-hidden" style="border-radius: 12px;">
                        <img src="{{ asset('storage/'.$item->file_path) }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <button wire:click="deleteItem({{ $item->id }})" class="btn btn-danger btn-icon btn-sm rounded-pill shadow-lg"><i class="ph-bold ph-trash"></i></button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <!-- FORM CREATE & EDIT -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
<!-- GANTI tag <form> Anda dengan ini -->
<form wire:submit.prevent="{{ $viewState == 'create' ? 'store' : 'confirmUpdate' }}">
    <div class="row">
        <!-- Sisi Kiri (Konten Utama) -->
        <div class="col-md-8 border-end-md">
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Judul Album <span class="text-danger">*</span></label>
                <!-- HAPUS .defer agar data langsung masuk ke variabel PHP -->
                <input type="text" class="form-control" wire:model="title" placeholder="Judul album kegiatan...">
                @error('title') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">Keterangan / Deskripsi</label>
                <textarea class="form-control" rows="8" wire:model="description" placeholder="Deskripsi singkat..."></textarea>
            </div>
        </div>

        <!-- Sisi Kanan (Pengaturan & Cover) -->
        <div class="col-md-4">
            <div class="p-3 bg-light rounded border mb-3">
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" wire:model="event_date">
                    @error('event_date') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold text-dark">Status Publikasi</label>
                    <select class="form-select" wire:model="status">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div class="p-3 bg-light rounded border">
                <label class="form-label fw-bold text-dark">Cover Album <span class="text-danger">*</span></label>
                <input type="file" class="form-control" wire:model="cover_image">

                <!-- Indikator Loading: Sangat penting untuk upload file -->
                <div wire:loading wire:target="cover_image" class="text-info small mt-1">
                    <i class="ph-bold ph-spinner spinner me-1"></i> Sedang memproses gambar...
                </div>

                @if($old_cover && !$cover_image)
                    <div class="mt-2 text-center bg-white p-2 rounded border shadow-sm">
                        <img src="{{ asset('storage/'.$old_cover) }}" class="img-fluid rounded" style="max-height: 120px;">
                    </div>
                @endif
                @if($cover_image)
                    <div class="mt-2 text-center bg-white p-2 rounded border shadow-sm">
                        <img src="{{ $cover_image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 120px;">
                    </div>
                @endif
                @error('cover_image') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <!-- FOOTER FORM -->
    <div class="border-top pt-3 mt-4 text-end">
        <button type="button" wire:click="showIndex" class="btn btn-link text-muted fw-bold">Cancel</button>

        <!-- PENTING: wire:target="cover_image" memastikan tombol menunggu sampai upload selesai -->
        <button type="submit" class="btn btn-primary px-4 shadow-sm"
            wire:loading.attr="disabled"
            wire:target="cover_image, store, update">
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

<script>
    // 1. Alert konfirmasi hapus
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah data dihapus?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) { @this.delete(id); }
        })
    }

    // 2. Alert konfirmasi Update (Dipicu dari event PHP)
    window.addEventListener('triggerUpdateConfirm', () => {
        Swal.fire({
            title: 'Apakah data diubah?',
            text: "Perubahan data akan disimpan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0070C0',
            confirmButtonText: 'Update'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.update();
            }
        })
    });

    // 3. Listener Notifikasi Sukses Universal
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



