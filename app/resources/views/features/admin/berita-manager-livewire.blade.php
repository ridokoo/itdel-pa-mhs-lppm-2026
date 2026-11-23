<div>
    <!-- STYLE CUSTOM KHUSUS TEMA PUTIH-HIJAU & FIX SWEETALERT -->
    <style>
        .content-inner, .content, .page-header, .card { background-color: #ffffff !important; color: #333333 !important; }
        .btn-primary { background-color: #0070C0 !important; border-color: #0070C0 !important; color: #ffffff !important; }
        .btn-primary:hover { background-color: #1bad82 !important; }
        .form-control, .form-select { background-color: #ffffff !important; border: 1px solid #ddd !important; color: #333 !important; }
        .form-control:focus, .form-select:focus { border-color: #0070C0 !important; box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25) !important; }
        .form-label { color: #212529 !important; font-weight: 600; }
        .table thead th { background-color: #f8f9fa !important; color: #444 !important; border-bottom: 2px solid #eee !important; font-weight: 700; text-align: center; }
        .page-title i { color: #0070C0 !important; }

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
                    <i class="ph-bold ph-newspaper me-2"></i>
                    @if($viewState == 'index') Manajemen Berita
                    @elseif($viewState == 'create') Tambah Berita Baru
                    @else Edit Berita @endif
                </h4>
            </div>
            <div class="collapse d-lg-block my-lg-auto ms-lg-auto">
                <div class="d-flex justify-content-center">
                    @if($viewState == 'index')
                        <button type="button" wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                            <i class="ph-bold ph-plus-circle me-2"></i> Tambah Berita
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
            <!-- TABLE VIEW -->
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Tanggal Event</th>
                                <th>Judul Berita</th>
                                <th style="width: 120px;">Status</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allNews as $item)
                            <tr class="align-middle" wire:key="news-{{ $item->id }}">
                                <td class="text-muted text-center">{{ \Carbon\Carbon::parse($item->event_date)->format('d M Y') }}</td>
                                <td class="fw-bold text-dark">{{ $item->title }}</td>
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
                            <tr><td colspan="4" class="text-center py-5 text-muted italic">Belum ada data berita.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- FORM VIEW -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
<form wire:submit.prevent="{{ $viewState == 'create' ? 'store' : 'update' }}">
                        <div class="row">
                            <!-- Sisi Kiri (Konten) -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Judul Berita</label>
                                    <input type="text" class="form-control" wire:model="title" placeholder="Masukkan judul...">
                                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Isi Berita Lengkap</label>
                                    <textarea class="form-control" rows="15" wire:model="content" placeholder="Tulis konten..."></textarea>
                                    @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Sisi Kanan (Pengaturan) -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border mb-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tanggal Event</label>
                                        <input type="date" class="form-control" wire:model="event_date">
                                        @error('event_date') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <select class="form-select" wire:model="status">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded border">
                                    <label class="form-label fw-bold">Gambar Utama</label>
                                    <input type="file" class="form-control" wire:model="image">

                                    {{-- Indikator Loading Saat Upload --}}
                                    <div wire:loading wire:target="image" class="text-primary small mt-1">
                                        <i class="ph-bold ph-spinner spinner me-1"></i> Mengunggah...
                                    </div>

                                    @if($old_image && !$image)
                                        <div class="mt-2 text-center bg-white p-2 rounded border"><img src="{{ asset('storage/'.$old_image) }}" class="img-fluid rounded" style="max-height: 150px;"></div>
                                    @endif
                                    @if($image)
                                        <div class="mt-2 text-center bg-white p-2 rounded border"><img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 150px;"></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER FORM -->
                        <div class="border-top pt-3 mt-4 text-end">
                            <button type="button" wire:click="showIndex" class="btn btn-link text-muted fw-bold">Cancel</button>

                            {{-- Tombol Simpan/Update --}}
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" wire:loading.attr="disabled" wire:target="image, store, update">
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

    <!-- JAVASCRIPT SWEETALERT -->
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
                confirmButtonText: 'Update',
                confirmButtonColor: '#0070C0',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.update(); // Menjalankan fungsi update di PHP
                }
            });
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



