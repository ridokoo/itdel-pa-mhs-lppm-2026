{{-- ============================================================ --}}
{{-- STEP 6: Unggah Proposal --}}
{{-- ============================================================ --}}
@if ($currentStep == 6)
    <div class="card border-0 shadow-sm p-4">
        <h6 class="fw-bold text-primary mb-3">6.1 Unggah Proposal Lengkap (Final)</h6>

        <div class="alert alert-info border-0 small mb-4" role="alert">
            <i class="ph-info me-2" aria-hidden="true"></i>
            Unggah dokumen proposal final yang sudah lengkap dengan lembar pengesahan dan lampiran pendukung lainnya dalam satu file PDF.
        </div>

        <div class="bg-light bg-opacity-10 p-3 rounded border border-dashed">

            @if ($existing_proposal && !isset($proposal_file))
                {{-- Gunakan DIV biasa karena input id="proposal_file" tidak ada di sini --}}
                <div class="d-flex align-items-center mb-2">
                    <span class="small fw-bold mb-0">
                        Berkas Proposal Final <span class="text-danger">*</span>
                    </span>
                </div>

                <div class="p-3 border rounded bg-dark mb-2 d-flex align-items-center shadow-sm">
                    <i class="ph-file-pdf ph-3x text-danger me-3" aria-hidden="true"></i>
                    <div class="flex-fill">
                        <div class="text-white fw-bold small">File Proposal Tersimpan:</div>
                        <a href="{{ asset('storage/' . $existing_proposal) }}" target="_blank"
                            class="text-info small text-decoration-underline">
                            {{ basename($existing_proposal) }}
                        </a>
                    </div>
                    @if (!$isReadOnly)
                        <button type="button" id="btn_change_proposal" name="btn_change_proposal"
                            wire:click="$set('existing_proposal', null)"
                            class="btn btn-secondary btn-sm rounded-pill px-3">
                            Ganti Berkas
                        </button>
                    @endif
                </div>
            @else
                {{-- Label hanya muncul di sini karena input id="proposal_file" dirender di sini --}}
                <div class="d-flex align-items-center mb-2">
                    <label class="small fw-bold mb-0" for="proposal_file">
                        Berkas Proposal Final <span class="text-danger">*</span>
                    </label>
                </div>

                <input type="file" id="proposal_file" name="proposal_file" wire:model="proposal_file"
                    class="form-control bg-dark text-white border-secondary" {{ $isReadOnly ? 'disabled' : '' }}
                    accept="application/pdf">

                @if ($proposal_file)
                    <div class="mt-2 p-2 bg-success bg-opacity-10 border border-success rounded d-flex align-items-center">
                        <i class="ph-check-circle text-success me-2" aria-hidden="true"></i>
                        <div class="small text-success fw-bold">
                            File terpilih: {{ $proposal_file->getClientOriginalName() }}
                        </div>
                    </div>
                @endif

                {{-- Indikator loading --}}
                <div wire:loading wire:target="proposal_file" class="text-primary fs-xs mt-1" role="status">
                    <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Sedang memproses berkas...
                </div>

                @error('proposal_file')
                    <span class="text-danger fs-xs d-block mt-1 fw-bold">{{ $message }}</span>
                @enderror
            @endif

            <small class="text-muted mt-2 d-block">* Format PDF, Maksimal 10MB. Nama file akan dipertahankan sesuai aslinya.</small>
        </div>

        {{-- FOOTER NAVIGASI --}}
        <div class="d-flex justify-content-end mt-4 gap-2 border-top pt-3">
            <button type="button" id="back_to_step5" name="back_to_step5" wire:click="nextStep(5)"
                class="btn btn-light rounded-pill px-4">
                <i class="ph-arrow-left me-2" aria-hidden="true"></i> Kembali
            </button>
            <button type="button" id="go_to_step7" name="go_to_step7" wire:click="nextStep(7)"
                class="btn btn-primary rounded-pill px-4">
                Lanjut ke Konfirmasi <i class="ph-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
@endif



