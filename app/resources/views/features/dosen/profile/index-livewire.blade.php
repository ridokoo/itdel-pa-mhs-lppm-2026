<div>
    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="ph-user-circle-plus me-2"></i> Lengkapi Biodata LPPM
                    </h5>
                </div>
                <div class="card-body bg-dark text-white">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-white">Nomor Induk Dosen Nasional (NIDN)</label>
                        <input type="text" wire:model.blur="nidn"
                            class="form-control bg-dark text-white border-secondary"
                            placeholder="Wajib diisi untuk pengajuan proposal">
                        @error('nidn') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-white">Program Studi</label>
                            <select wire:model.blur="study_program_id"
                                class="form-select bg-dark text-white border-secondary">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($listProdi as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-white">Jabatan Fungsional</label>
                            <select wire:model.blur="functional_position_id"
                                class="form-select bg-dark text-white border-secondary">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($listJabfung as $jab)
                                    <option value="{{ $jab->id }}">{{ $jab->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Email --}}
<div class="mb-3">
    <label class="form-label fw-bold text-white">
        Email <span class="text-danger">*</span>
    </label>

    @if (!empty($userApi->email))
        {{-- Email sudah ada dari SSO, read-only --}}
        <input type="text"
            class="form-control bg-dark text-white border-secondary"
            value="{{ $userApi->email }}"
            readonly disabled>
        <small class="text-muted">Email diambil dari akun SSO dan tidak dapat diubah.</small>
    @else
        {{-- Email belum ada, wajib diisi manual --}}
        <input type="email" wire:model.blur="email"
            class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
            placeholder="Masukkan email aktif Anda">
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        <small class="text-warning">
            <i class="ph-warning me-1"></i> Email Anda belum terdaftar di SSO. Wajib diisi untuk melanjutkan.
        </small>
    @endif
</div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-white">
                            Nomor WhatsApp Aktif <span class="text-muted small fw-normal">(Opsional)</span>
                        </label>
                        <input type="text" wire:model.blur="phone_number"
                            class="form-control bg-dark text-white border-secondary"
                            placeholder="Contoh: 08123456789">
                        @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                </div>
            </div>
        </div>

        <!-- PROFILE KANAN -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body bg-black text-center">
                    <div class="mb-3">
                        <img src="{{ $userApi->photo }}" class="rounded-circle border shadow-sm"
                            width="110" height="110" style="object-fit:cover;">
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $userApi->name }}</h5>
                    <p class="text-muted small mb-2">{{ $userApi->email }}</p>
                    <span class="badge bg-success px-3 py-2">
                        <i class="ph-check-circle me-1"></i> Akun SSO Terverifikasi
                    </span>
                    <div class="mt-4 pt-3 border-top text-start">
                        <small class="text-uppercase text-secondary fw-bold d-block mb-3">Data Pusat (CIS)</small>
                        <div class="mb-2 text-dark">
                            <i class="ph-user me-2 text-primary"></i>
                            <strong>Username:</strong> {{ $userApi->username }}
                        </div>
                        <div class="text-dark">
                            <i class="ph-identification-card me-2 text-primary"></i>
                            <strong>Role:</strong> {{ $userApi->alias }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ID PENELITI -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">
                    <i class="ph-fingerprint me-2"></i> ID Peneliti (Scopus, SINTA, dll)
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Mohon lengkapi ID Peneliti Anda untuk sinkronisasi data publikasi.</p>

                @forelse ($identifiers as $i => $row)
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-3">
                            <select wire:model="identifiers.{{ $i }}.index_id" class="form-select">
                                <option value="">-- Pilih ID --</option>
                                @foreach ($indexOptions as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" wire:model.blur="identifiers.{{ $i }}.author_id"
                                class="form-control" placeholder="Masukkan ID">
                        </div>
                        <div class="col-md-4">
                            <input type="text" wire:model.blur="identifiers.{{ $i }}.profile_url"
                                class="form-control" placeholder="URL Profil (Opsional)">
                        </div>
                        <div class="col-md-2">
                            <button type="button" wire:click="removeIdentifier({{ $i }})"
                                class="btn btn-danger w-100">✕</button>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center">Belum ada ID ditambahkan</div>
                @endforelse

                <button type="button" wire:click="addIdentifier" class="btn btn-outline-success mt-2">
                    + Tambah ID
                </button>
            </div>
        </div>

        {{-- SATU TOMBOL SIMPAN --}}
        <div class="d-grid mt-3">
            <button wire:click="saveAll" class="btn btn-success btn-lg">
                <i class="ph-floppy-disk me-2"></i> Simpan Semua Perubahan
            </button>
            <div wire:loading wire:target="saveAll" class="text-center text-muted small mt-2">
                <span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...
            </div>
        </div>

    </div>
</div>



