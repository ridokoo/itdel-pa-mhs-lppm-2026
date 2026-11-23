<div>
    <!-- STYLE CUSTOM KHUSUS TEMA PUTIH-HIJAU & SWEETALERT FIX -->
    <style>
        /* Paksa area konten & header menjadi putih */
        .content, .page-header, .card {
            background-color: #ffffff !important;
            color: #333333 !important;
        }

        /* Tombol Utama Hijau #0070C0 */
        .btn-primary {
            background-color: #0070C0 !important;
            border-color: #0070C0 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover {
            background-color: #1bad82 !important;
        }

        /* Header Tabel Modern */
        .table thead th {
            background-color: #f8f9fa !important;
            color: #444 !important;
            border-bottom: 2px solid #eee !important;
            font-weight: 700;
            text-align: center;
        }

        /* Icon Header */
        .page-title i {
            color: #0070C0 !important;
        }

        /* Card Shadow halus */
        .card {
            border: 1px solid #f0f0f0 !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important;
            border-radius: 12px !important;
        }

        /* Input styling */
        .form-control:focus, .form-select:focus {
            border-color: #0070C0 !important;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important;
        }

        /* Badge khusus urutan */
        .badge-order {
            background-color: #f0fdf4;
            color: #0070C0;
            border: 1px solid #dcfce7;
        }

        /* FIX TOTAL UNTUK IKON SWEETALERT DI LIMITLESS */
        .swal2-icon {
            width: 80px !important;
            height: 80px !important;
            min-width: 80px !important;
            min-height: 80px !important;
            border-width: 4px !important;
            margin: 20px auto 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: content-box !important;
        }
        .swal2-icon .swal2-icon-content {
            font-size: 50px !important;
            display: flex !important;
            align-items: center !important;
        }
        .swal2-success-ring {
            width: 80px !important;
            height: 80px !important;
            box-sizing: content-box !important;
        }
        .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #0070C0 !important;
            height: 5px !important;
        }
        .swal2-timer-progress-bar {
            background-color: #0070C0 !important;
        }
        .swal2-title {
            font-size: 1.4rem !important;
            padding-top: 10px !important;
            font-weight: 700 !important;
            color: #333 !important;
        }
        .swal2-popup {
            border-radius: 15px !important;
            padding: 1.5rem !important;
            width: 22rem !important;
        }
    </style>

    <!-- HEADER HALAMAN -->
    <div class="page-header border-bottom mb-3 bg-white">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex py-2">
                <h4 class="page-title mb-0 text-dark fw-bold">
                    <i class="ph-bold ph-address-book me-2"></i>
                    @if($viewState == 'index') Manajemen Kontak
                    @elseif($viewState == 'create') Tambah Kontak Baru
                    @else Edit Kontak @endif
                </h4>
            </div>
            <div class="collapse d-lg-block my-lg-auto ms-lg-auto">
                <div class="d-flex justify-content-center">
                    @if($viewState == 'index')
                        <button wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                            <i class="ph-bold ph-plus-circle me-2"></i> Tambah Kontak
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

    <!-- AREA KONTEN -->
    <div class="content">
        @if($viewState == 'index')
            <!-- TABEL DAFTAR KONTAK -->
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr>
                                <th class="text-center">Ikon</th>
                                <th class="text-center">Nama Kontak</th>
                                <th class="text-center">Nilai / Alamat</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $item)
                            <tr class="align-middle" wire:key="contact-{{ $item->id }}">
                                <td class="text-center">
                                    @if($item->icon)
                                        @php
                                            $iconClass = trim($item->icon);
                                            if (!str_contains($iconClass, 'ph-')) { $iconClass = 'ph-bold ph-' . $iconClass; }
                                            elseif (!str_contains($iconClass, 'ph-bold')) { $iconClass = 'ph-bold ' . $iconClass; }
                                        @endphp
                                        <i class="{{ $iconClass }} fs-3" style="color: #0070C0;"></i>
                                    @elseif($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" width="32" class="rounded shadow-sm">
                                    @else
                                        <i class="ph-bold ph-question fs-3 text-muted"></i>
                                    @endif
                                </td>
                                <td class="fw-bold text-dark text-center">{{ $item->name }}</td>
                                <td class="text-center">
                                    <span style="color: #333333 !important; font-weight: 500;">{{ $item->contact_value }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <button wire:click="showEdit({{ $item->id }})" class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent me-2" title="Ubah">
                                            <i class="ph-bold ph-pencil-line"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete({{ $item->id }})" class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent" title="Hapus">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="ph-bold ph-magnifying-glass fs-2 d-block mb-2 opacity-25"></i>
                                    Belum ada data kontak tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <!-- FORM INPUT KONTAK -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    {{-- PERBAIKAN: Gunakan wire:submit standar Livewire untuk kestabilan --}}
                    <form wire:submit.prevent="{{ $viewState == 'create' ? 'store' : 'confirmUpdate' }}">
                        <div class="row">
                            <!-- KONTEN UTAMA -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Nama Kontak</label>
                                    <input type="text" class="form-control border-light shadow-sm" wire:model="name" placeholder="WhatsApp LPPM, Email, dll">
                                    @error('name') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Nilai / Alamat Kontak</label>
                                    <input type="text" class="form-control border-light shadow-sm" wire:model="contact_value" placeholder="lppm@del.ac.id atau 0812...">
                                    @error('contact_value') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- PENGATURAN -->
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded border mb-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Urutan Tampil</label>
                                        <input type="number" class="form-control border-light shadow-sm" wire:model="display_order">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-bold text-dark">Ikon (Cukup ketik: phone)</label>
                                        <input type="text" class="form-control border-light shadow-sm" wire:model="icon" placeholder="whatsapp-logo">
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded border">
                                    <label class="form-label fw-bold text-dark">Gambar (Opsional)</label>
                                    <input type="file" class="form-control border-light shadow-sm" wire:model="image">

                                    <div wire:loading wire:target="image" class="text-info fs-xs mt-1">
                                        <i class="ph-bold ph-spinner spinner me-1"></i> Mengunggah gambar...
                                    </div>

                                    @if($old_image && !$image)
                                        <div class="mt-2 text-center bg-white p-2 rounded border shadow-sm">
                                            <img src="{{ asset('storage/'.$old_image) }}" class="img-fluid rounded" style="max-height: 80px;">
                                        </div>
                                    @endif

                                    @if($image)
                                        <div class="mt-2 text-center bg-white p-2 rounded border shadow-sm">
                                            <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 80px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER FORM -->
                        <div class="border-top pt-3 mt-4 text-end">
                            <button type="button" wire:click="showIndex" class="btn btn-link text-muted fw-bold">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                wire:loading.attr="disabled"
                                wire:target="image, store, update">
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
        // 1. Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah data dihapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(id);
                }
            })
        }

        // 2. Konfirmasi Update (Pemicu dari event Livewire)
        window.addEventListener('triggerUpdateConfirm', () => {
            Swal.fire({
                title: 'Apakah data diubah?',
                text: "Perubahan data akan disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0070C0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.update();
                }
            })
        });

        // 3. Notifikasi Berhasil dengan Progress Bar
        window.addEventListener('swal:modal', event => {
            let data = event.detail[0] ? event.detail[0] : event.detail;

            Swal.fire({
                title: data.title || 'Informasi',
                text: data.text || '',
                icon: data.icon || 'info',
                confirmButtonColor: '#0070C0',
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
</div>



