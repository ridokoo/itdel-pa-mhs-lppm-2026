{{-- ============================================================ --}}
{{-- STEP 2: Personil --}}
{{-- ============================================================ --}}
@if ($currentStep == 2)
    <div class="card border-0 shadow-sm overflow-hidden">
        {{-- HEADER INFO KUOTA --}}
        <div class="card-header bg-dark text-white d-flex align-items-center py-3">
            <div class="bg-white bg-opacity-20 rounded-pill p-2 me-3">
                <i class="ph-users-three ph-2x text-white" aria-hidden="true"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white">Manajemen Personil Tim</h6>
                @php
                    $currentTotal = count($addedDosen) + count($addedStaff);
                    $maxLimit = $this->getMaxMemberLimit();
                    $isFull = $currentTotal >= $maxLimit;
                    $percent = $maxLimit > 0 ? ($currentTotal / $maxLimit) * 100 : 0;
                @endphp
                <div class="d-flex align-items-center mt-1">
                    <div class="progress flex-grow-1 mb-0 me-2" style="height: 6px; width: 100px;">
                        <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-success' }}"
                            style="width: {{ $percent }}%"></div>
                    </div>
                    {{-- Perbaikan: Ganti text-white menjadi text-light agar lebih kontras --}}
                    <small class="fw-semibold {{ $isFull ? 'text-danger' : 'text-light' }}">
                        {{ $currentTotal }} / {{ $maxLimit }} Kuota Terisi (Dosen & Staff)
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- 2.1 IDENTITAS KETUA --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">2.1 Identitas Ketua Pengusul</h6>
                <div class="row align-items-center">
                    <div class="col-md-auto mb-2 mb-md-0 text-center">
                        <img src="{{ $localUser?->photo ?? asset('assets/images/demo/users/face11.jpg') }}"
                            class="rounded-circle border border-width-3 border-light shadow-sm" width="60"
                            alt="Foto Ketua">
                    </div>
                    <div class="col-md">
                        <div class="fw-bold fs-base text-dark">{{ strtoupper($localUser?->name) }}</div>
                        <div class="text-muted small">NIDN:
                            <span class="fw-bold text-primary">
                                {{ $userProfile?->nidn ?? ($localUser?->nidn ?? '-') }}
                            </span>
                        </div>
                        {{-- Perbaikan: Pakai bg-primary solid agar putihnya kontras --}}
                        <span class="badge bg-primary text-white mt-1 shadow-sm px-2">KETUA PENGUSUL</span>
                    </div>
                </div>
            </div>

            {{-- 2.2 ANGGOTA DOSEN --}}
            <div class="mb-4 border-start border-start-width-3 border-start-info ps-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-primary mb-0 flex-grow-1">2.2 Anggota Peneliti (Dosen)</h6>
                    <div class="ms-3">
                        @if (!$isFull && !$isReadOnly)
                            <button type="button" id="btn_tambah_dosen" name="btn_tambah_dosen"
                                wire:click="openSearch('dosen')" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="ph-plus me-1"></i> Tambah Dosen
                            </button>
                        @elseif ($isFull)
                            {{-- Perbaikan: Pakai bg-danger solid agar teks putih terlihat --}}
                            <span class="badge bg-danger text-white px-2 py-1 shadow-sm">
                                <i class="ph-warning-circle me-1"></i> Kuota Penuh
                            </span>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm small align-middle">
                        <thead class="bg-light text-center text-dark fw-bold">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 200px;">Identitas</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center" style="width: 50px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addedDosen as $index => $m)
                                <tr>
                                    <td class="text-center text-dark">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <div class="fw-bold text-dark">{{ $m['id_no'] }}</div>
                                        {{-- Perbaikan: Ganti bg-opacity menjadi solid warna info --}}
                                        <span class="badge bg-info text-white" style="font-size: 9px;">DOSEN</span>
                                    </td>
                                    <td class="text-dark">{{ strtoupper($m['name']) }}</td>
                                    <td class="text-center">
                                        @if (!$isReadOnly)
                                            <button type="button" wire:click="removeDosen({{ $index }})"
                                                class="btn btn-sm text-danger p-0">
                                                <i class="ph-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-2">Belum ada anggota dosen.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2.3 ANGGOTA STAFF --}}
            <div class="mb-4 border-start border-start-width-3 border-start-info ps-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-primary mb-0 flex-grow-1">2.3 Anggota Peneliti Non-Dosen (Staff)</h6>
                    <div class="ms-3">
                        @if (!$isFull && !$isReadOnly)
                            <button type="button" id="btn_tambah_staff" name="btn_tambah_staff"
                                wire:click="openSearch('staff')" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="ph-plus me-1"></i> Tambah Staff
                            </button>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm small align-middle">
                        <thead class="bg-light text-center text-dark fw-bold">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 200px;">Identitas</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center" style="width: 50px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addedStaff as $index => $m)
                                <tr>
                                    <td class="text-center text-dark">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <div class="fw-bold text-dark">{{ $m['id_no'] }}</div>
                                        {{-- Perbaikan: Pakai bg-secondary solid --}}
                                        <span class="badge bg-secondary text-white" style="font-size: 9px;">STAFF</span>
                                    </td>
                                    <td class="text-dark">{{ strtoupper($m['name']) }}</td>
                                    <td class="text-center">
                                        @if (!$isReadOnly)
                                            <button type="button" wire:click="removeStaff({{ $index }})"
                                                class="btn btn-sm text-danger p-0">
                                                <i class="ph-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-2">Belum ada anggota staff.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2.4 ANGGOTA MAHASISWA --}}
            <div class="mb-4 border-start border-start-width-3 border-start-info ps-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-primary mb-0">2.4 Anggota Peneliti Mahasiswa</h6>
                        <small class="text-muted italic">Mahasiswa tidak dihitung dalam batas kuota skema.</small>
                    </div>
                    <div class="ms-3">
                        @if (!$isReadOnly)
                            <button type="button" id="btn_tambah_mhs" name="btn_tambah_mhs"
                                wire:click="openSearch('mahasiswa')" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="ph-plus me-1"></i> Tambah Mahasiswa
                            </button>
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm small align-middle">
                        <thead class="bg-light text-center text-dark fw-bold">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 200px;">Identitas</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center" style="width: 50px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($addedMahasiswa as $index => $m)
                                <tr>
                                    <td class="text-center text-dark">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <div class="fw-bold text-dark">{{ $m['id_no'] }}</div>
                                        {{-- Perbaikan: Pakai bg-success solid --}}
                                        <span class="badge bg-success text-white" style="font-size: 9px;">MAHASISWA</span>
                                    </td>
                                    <td class="text-dark">{{ strtoupper($m['name']) }}</td>
                                    <td class="text-center">
                                        @if (!$isReadOnly)
                                            <button type="button" wire:click="removeMahasiswa({{ $index }})"
                                                class="btn btn-sm text-danger p-0">
                                                <i class="ph-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-2">Belum ada anggota mahasiswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER NAVIGASI --}}
            <div class="d-flex justify-content-end mt-5 gap-2 border-top pt-3">
                <button type="button" wire:click="nextStep(1)" class="btn btn-light rounded-pill px-4 shadow-sm">
                    <i class="ph-arrow-left me-2"></i> Kembali
                </button>
                <button type="button" wire:click="nextStep(3)" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    Lanjut ke Luaran <i class="ph-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
@endif



