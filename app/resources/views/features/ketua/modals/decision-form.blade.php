<div wire:ignore.self class="modal fade" id="modal_putusan_ketua" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white border-0">
                <h6 class="modal-title fw-bold">
                    <i class="ph-seal-check me-2"></i> Lembar Keputusan Ketua LPPM
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            @if ($selectedProposal)
                @php
                    $isReady = !empty($decision_note) && strlen(trim($decision_note)) >= 10;
                @endphp

                <div class="modal-body p-0">

                    {{-- 1. REKOMENDASI REVIEWER (READ ONLY) --}}
                    <div class="p-3 bg-light border-bottom">
                        <h6 class="fw-bold text-indigo mb-2 small text-uppercase">
                            <i class="ph-clipboard-text me-1"></i> Rekomendasi Reviewer:
                        </h6>
                        @forelse ($selectedProposal->reviewers as $rev)
                            <div class="mb-2 p-2 bg-white rounded border-start border-start-width-3 border-start-indigo">
                                <div class="fw-bold small">{{ $rev->reviewer?->name }}</div>
                                <div class="fst-italic small text-muted mt-1">
                                    "{{ $rev->notes ?? 'Tidak ada catatan.' }}"
                                </div>
                            </div>
                        @empty
                            <div class="text-muted small fst-italic">
                                <i class="ph-info me-1"></i> Belum ada rekomendasi dari reviewer.
                            </div>
                        @endforelse
                    </div>

                    {{-- 2. FORM PUTUSAN --}}
                    <div class="p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Catatan Keputusan Final
                                <span class="text-danger">*</span>
                                <span class="text-muted fw-normal small">(min. 10 karakter)</span>
                            </label>
                            <textarea wire:model.live="decision_note"
                                class="form-control {{ !$isReady && !$isReadOnly ? 'border-warning' : '' }}"
                                rows="4"
                                placeholder="Berikan alasan, instruksi revisi, atau catatan keputusan..."
                                @if ($isReadOnly) readonly @endif></textarea>

                            @if (!$isReadOnly)
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="{{ !$isReady ? 'text-warning fw-semibold' : 'text-success fw-semibold' }}">
                                        @if (!$isReady)
                                            <i class="ph-warning me-1"></i>
                                            Kurang {{ max(0, 10 - strlen(trim($decision_note ?? ''))) }} karakter lagi
                                        @else
                                            <i class="ph-check-circle me-1"></i> Catatan sudah cukup
                                        @endif
                                    </small>
                                    <small class="text-muted">{{ strlen(trim($decision_note ?? '')) }} karakter</small>
                                </div>
                            @endif
                        </div>

                        {{-- Pesan terkunci --}}
                        @if (!$isReady && !$isReadOnly)
                            <div class="alert alert-secondary border-0 py-2 small">
                                <i class="ph-lock me-1"></i>
                                Semua tombol keputusan terkunci. Isi catatan minimal 10 karakter untuk membuka.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 3. FOOTER TOMBOL KEPUTUSAN DINAMIS --}}
                <div class="modal-footer border-0 bg-light justify-content-center py-3 gap-2 flex-wrap">
                    @if (!$isReadOnly)
                        @if ($statusFinalOptions->isEmpty())
                            <div class="alert alert-warning mb-0 small w-100 text-center py-2">
                                <i class="ph-warning-circle me-2"></i>
                                Belum ada status final. Hubungi operator untuk menambahkan status.
                            </div>
                        @else
                            @foreach ($statusFinalOptions as $opt)
                                @php
                                    $sName = strtolower($opt->name);
                                    $btnClass = match(true) {
                                        str_contains($sName, 'terima') && !str_contains($sName, 'revisi') => 'btn-success',
                                        str_contains($sName, 'revisi')                                     => 'btn-warning text-white',
                                        str_contains($sName, 'tolak')                                      => 'btn-danger',
                                        default                                                             => 'btn-primary',
                                    };
                                    $icon = match(true) {
                                        str_contains($sName, 'terima') && !str_contains($sName, 'revisi') => 'ph-check-circle',
                                        str_contains($sName, 'revisi')                                     => 'ph-pencil-line',
                                        str_contains($sName, 'tolak')                                      => 'ph-x-circle',
                                        default                                                             => 'ph-seal-check',
                                    };
                                @endphp
                                <button type="button"
                                    wire:click="confirmDecision({{ $opt->id }})"
                                    wire:loading.attr="disabled"
                                    @if (!$isReady) disabled @endif
                                    class="btn rounded-pill px-4 shadow-sm fw-bold {{ $isReady ? $btnClass : 'btn-secondary' }}">
                                    <i class="{{ $icon }} me-1"></i>
                                    {{ strtoupper($opt->name) }}
                                </button>
                            @endforeach
                        @endif
                    @else
                        <div class="alert alert-secondary mb-0 small w-100 text-center py-2">
                            <i class="ph-info me-2"></i> Keputusan sudah diberikan — hanya bisa dilihat.
                        </div>
                    @endif
                </div>

            @endif

        </div>
    </div>
</div>



