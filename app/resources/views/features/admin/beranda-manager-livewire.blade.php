<div>
    <!-- STYLE CUSTOM KHUSUS TEMA PUTIH-HIJAU -->
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

        /* Badge Tipe Konten */
        .table tbody td .badge.bg-light {
            background: linear-gradient(135deg, #0070C0, #0d86df) !important;
            color: #ffffff !important;
            border: none !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            letter-spacing: 0.4px;
            padding: 8px 14px !important;
            border-radius: 10px !important;
            text-transform: uppercase;
            box-shadow: 0 3px 8px rgba(0, 112, 192, 0.18);
            transition: all 0.2s ease;
        }

        /* Hover badge tipe konten */
        .table tbody td .badge.bg-light:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(0, 112, 192, 0.22);
        }

        /* Hover tabel */
        .table-hover tbody tr:hover {
            background-color: #f5f9ff !important;
            transition: 0.2s ease;
        }

     /* FIX TOTAL UNTUK IKON SWEETALERT DI LIMITLESS */
    .swal2-icon {
        width: 80px !important;         /* Ukuran lingkaran */
        height: 80px !important;        /* Ukuran lingkaran */
        min-width: 80px !important;
        min-height: 80px !important;
        border-width: 4px !important;
        margin: 20px auto 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: content-box !important;
    }

    /* 2. Mengatur ukuran simbol (tanda tanya, centang, dll) di dalam lingkaran */
    .swal2-icon .swal2-icon-content {
        font-size: 50px !important;     /* Ukuran simbol ? atau ! */
        display: flex !important;
        align-items: center !important;
    }

    /* 3. Perbaikan khusus untuk tanda centang (Success) agar tidak pecah */
    .swal2-success-ring {
        width: 80px !important;
        height: 80px !important;
        box-sizing: content-box !important;
    }

    .swal2-icon.swal2-success [class^='swal2-success-line'] {
        background-color: #0070C0 !important;
        height: 5px !important;
    }

    /* 4. Perbaikan teks judul agar tidak tertutup ikon */
    .swal2-title {
        font-size: 1.4rem !important;
        padding-top: 10px !important;
        font-weight: 700 !important;
        color: #333 !important;
    }

    /* 5. Mengatur ukuran popup agar lebih manis */
    .swal2-popup {
        border-radius: 15px !important;
        padding: 1.5rem !important;
        width: 22rem !important; /* Membuat kotak modal lebih pas (tidak terlalu lebar) */
    }

    .swal2-timer-progress-bar {
        background-color: #0070C0 !important;
    }
    </style>

    <!-- HEADER HALAMAN -->
    <div class="page-header border-bottom mb-3 bg-white">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex py-2">
                <h4 class="page-title mb-0 text-dark fw-bold">
                    <i class="ph-bold ph-house me-2"></i>
                    @if($viewState == 'index') Manajemen Beranda
                    @elseif($viewState == 'create') Tambah Konten Beranda
                    @else Edit Konten Beranda @endif
                </h4>
            </div>
            <div class="collapse d-lg-block my-lg-auto ms-lg-auto">
                <div class="d-flex justify-content-center">
                    @if($viewState == 'index')
                        <button wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                            <i class="ph-bold ph-plus-circle me-2"></i> Tambah Konten
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
            <!-- TABEL DAFTAR KONTEN BERANDA -->
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover bg-white">
                        <thead>
                            <tr>
                                <th class="text-center">Tipe Konten</th>
                                <th class="text-center">Judul Konten</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($profiles as $p)
                            <tr class="align-middle" wire:key="profile-{{ $p->id }}">
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold">
                                        {{ str_replace('_', ' ', strtoupper($p->content_type)) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">{{ $p->title }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $p->status == 'published' ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                                        {{ strtoupper($p->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <button wire:click="showEdit({{ $p->id }})" class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent me-2" title="Ubah">
                                            <i class="ph-bold ph-pencil-line"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete({{ $p->id }})" class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent" title="Hapus">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="ph-bold ph-magnifying-glass fs-2 d-block mb-2 opacity-25"></i>
                                    Belum ada konten untuk halaman beranda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <!-- FORM INPUT -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    {{-- Gunakan wire:submit agar data tersinkronisasi sempurna --}}
                    <form wire:submit.prevent="{{ $viewState == 'create' ? 'store' : 'confirmUpdate' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Judul Konten</label>
                                    {{-- HAPUS .defer agar data langsung masuk ke PHP --}}
                                    <input type="text" class="form-control border-light shadow-sm" wire:model="title" placeholder="Masukkan judul konten...">
                                    @error('title') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Isi / Deskripsi Konten</label>
                                    <textarea class="form-control border-light shadow-sm" rows="12" wire:model="content" placeholder="Tulis deskripsi konten di sini..."></textarea>
                                    @error('content') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded border mb-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Tipe Konten</label>
                                        <select class="form-select border-light shadow-sm" wire:model="content_type">
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="profile">Profil LPPM</option>
                                            <option value="vision_mission">Visi & Misi</option>
                                            <option value="system_description">Deskripsi Sistem</option>
                                            <option value="greeting">Kata Sambutan</option>
                                            <option value="strategic_goals">Tujuan Strategis</option>
                                            <option value="organization_structure">Struktur Organisasi</option>
                                        </select>
                                        @error('content_type') <span class="text-danger fs-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark">Urutan Tampil</label>
                                        <input type="number" class="form-control border-light shadow-sm" wire:model="display_order">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-bold text-dark">Status Publikasi</label>
                                        <select class="form-select border-light shadow-sm" wire:model="status">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded border">
                                    <label class="form-label fw-bold text-dark">Gambar (Opsional)</label>
                                    <input type="file" class="form-control border-light shadow-sm" wire:model="image">
                                    <div wire:loading wire:target="image" class="text-info fs-xs mt-1">Mengunggah...</div>

                                    @if($old_image && !$image)
                                        <div class="mt-2 text-center bg-white p-2 rounded border shadow-sm">
                                            <img src="{{ asset('storage/'.$old_image) }}" class="img-fluid rounded" style="max-height: 150px;">
                                        </div>
                                    @endif

                                    @if($image)
                                        <div class="mt-2 text-center bg-white p-2 rounded border shadow-sm">
                                            {{-- Tambahkan pengecekan try-catch sederhana atau cek ekstensi --}}
                                            @php
                                                $isPreviewable = in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif']);
                                            @endphp

                                            @if($isPreviewable)
                                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 150px;">
                                            @else
                                                <div class="p-3 text-muted">
                                                    <i class="ph-bold ph-file-search fs-1"></i>
                                                    <p class="small">File .{{ $image->getClientOriginalExtension() }} tidak dapat dipratinjau, tapi tetap bisa disimpan.</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER FORM -->
                        <div class="border-top pt-3 mt-4 text-end">
                            <button type="button" wire:click="showIndex" class="btn btn-link text-muted fw-bold">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" wire:loading.attr="disabled" wire:target="image, store, update">
                                <i class="ph-bold ph-floppy-disk me-2"></i>
                                <span wire:loading.remove wire:target="store, update">
                                    {{ $viewState == 'create' ? 'Simpan Data' : 'Update' }}
                                </span>
                                <span wire:loading wire:target="store, update">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- JAVASCRIPT UNTUK SWEETALERT -->
    <script>
        // 1. Alert konfirmasi hapus
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

        // 3. Alert konfirmasi Update (Baru)
        window.addEventListener('triggerUpdateConfirm', () => {
            Swal.fire({
                title: 'Apakah data diubah?',
                text: "Perubahan data akan disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0070C0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) { @this.update(); }
            })
        });

        // 2. Listener Universal untuk Livewire v3
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



