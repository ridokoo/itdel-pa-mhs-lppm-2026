{{-- ============================================================ --}}
{{-- STEP 5: Mitra --}}
{{-- ============================================================ --}}
@if ($currentStep == 5)
    <div class="card border-0 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h6 class="fw-bold text-primary mb-0">
                5.1 Data Mitra
                @if ($categoryId == 7)
                    <span class="text-danger small ms-2">(Wajib untuk Pengabdian)</span>
                @else
                    <span class="text-muted small ms-2 fw-normal">(Opsional untuk Penelitian)</span>
                @endif
            </h6>
            @if (!$isReadOnly)
                {{-- GANTI btn-success MENJADI btn-primary --}}
                <button type="button"
                    id="btn_tambah_mitra_modal"
                    name="btn_tambah_mitra_modal"
                    class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm"
                    wire:click="$dispatch('showModal', {id: '#modal_tambah_mitra'})">
                    <i class="ph-plus-circle me-2"></i> Tambah Mitra
                </button>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase text-center">
                        <th scope="col" style="width: 50px;">No.</th>
                        <th scope="col">Nomor Identitas</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Email</th>
                        <th scope="col">Institusi</th>
                        <th scope="col">Kualifikasi</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Deskripsi Peran</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse ($addedMitra as $index => $m)
                        <tr wire:key="mitra-{{ $index }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-semibold text-center">{{ $m['identity_number'] }}</td>
                            <td class="text-primary fw-bold text-center">{{ $m['name'] }}</td>
                            <td class="text-center">{{ $m['email'] }}</td>
                            <td class="text-center">{{ $m['institution'] }}</td>
                            <td class="text-center">{{ $m['qualification'] ?? '-' }}</td>
                            <td class="text-truncate text-center" style="max-width: 150px;" title="{{ $m['address'] }}">
                                {{ $m['address'] }}
                            </td>
                            <td class="text-truncate text-center" style="max-width: 150px;" title="{{ $m['role_description'] }}">
                                {{ $m['role_description'] }}
                            </td>
                            <td class="text-center">
                                @if (!$isReadOnly)
                                    <button type="button"
                                        id="remove_mitra_{{ $index }}"
                                        name="remove_mitra_{{ $index }}"
                                        wire:click="removeMitra({{ $index }})"
                                        class="btn btn-sm btn-light text-danger p-1">
                                        <i class="ph-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="ph-users-three ph-3x opacity-20 d-block mb-2"></i>
                                Belum ada data mitra. Klik tombol "Tambah Mitra" jika usulan Anda melibatkan pihak luar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER NAVIGASI --}}
        <div class="d-flex justify-content-end mt-4 gap-2 border-top pt-3">
            <button type="button" wire:click="nextStep(4)" class="btn btn-light rounded-pill px-4 shadow-sm">Kembali</button>
            <button type="button" wire:click="nextStep(6)" class="btn btn-primary rounded-pill px-4 shadow-sm">
                Lanjut ke Unggah <i class="ph-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
@endif



