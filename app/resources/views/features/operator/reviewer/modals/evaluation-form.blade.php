<div wire:ignore.self class="modal fade" id="modal_evaluasi" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white border-0">
                <h6 class="modal-title fw-bold">
                    <i class="ph-clipboard-text me-2"></i> Lembar Evaluasi & Detail Usulan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            @if ($selectedProposal)
                <div class="modal-body p-0">
                    <div class="row g-0">

                        {{-- ── KIRI: DETAIL USULAN (READ ONLY) ── --}}
                        <div class="col-md-8 border-end" style="max-height: 80vh; overflow-y: auto;">
                            <div class="p-4">
                                <h5 class="fw-bold text-primary mb-3">{{ $selectedProposal->title }}</h5>

                                {{-- I. Ringkasan & Berkas --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold border-bottom pb-1">I. Ringkasan & Berkas</h6>
                                    <p class="small text-justify">
                                        {{ $selectedProposal->summary ?? 'Tidak ada ringkasan.' }}
                                    </p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @if ($selectedProposal->proposal_file)
                                            <a href="{{ asset('storage/' . $selectedProposal->proposal_file) }}"
                                                target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="ph-file-pdf me-1"></i> File Proposal Final
                                            </a>
                                        @endif
                                        @php
                                            $substansi = $selectedProposal->proposalOutputTargets->first()?->file_path;
                                        @endphp
                                        @if ($substansi)
                                            <a href="{{ asset('storage/' . $substansi) }}" target="_blank"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="ph-file-pdf me-1"></i> File Substansi
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- II. Personil Tim --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold border-bottom pb-1">II. Personil Tim</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light small">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Identitas</th>
                                                <th>Peran</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            <tr>
                                                <td>{{ $selectedProposal->leader->name }}</td>
                                                <td>{{ $selectedProposal->leader->nidn ?? '-' }}</td>
                                                <td><span class="badge bg-primary">Ketua</span></td>
                                            </tr>
                                            @foreach ($selectedProposal->proposalMembers as $m)
                                                <tr>
                                                    <td>{{ $m->user->name }}</td>
                                                    <td>{{ $m->user->nidn ?? ($m->user->nim ?? '-') }}</td>
                                                    <td><span class="badge bg-secondary">Anggota</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- III. Rincian RAB --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold border-bottom pb-1">III. Rencana Anggaran (RAB)</h6>
                                    <table class="table table-sm table-bordered">
                                        <tbody class="small">
                                            @foreach ($selectedProposal->budgetItems as $item)
                                                <tr>
                                                    <td>{{ $item->item_name }}</td>
                                                    <td class="text-end">
                                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="table-dark fw-bold">
                                                <td>TOTAL KESELURUHAN</td>
                                                <td class="text-end">
                                                    Rp
                                                    {{ number_format($selectedProposal->total_budget, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- ── KANAN: FORM PENILAIAN ── --}}
                        <div class="col-md-4 bg-light">
                            <div class="p-4 h-100 d-flex flex-column">

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status Rekomendasi</label>
                                        {{-- KUNCI JIKA READ ONLY --}}
                                        <select wire:model="rev_status_id" class="form-select"
                                            @if ($isReadOnly) disabled @endif>
                                            <option value="">-- Pilih Status --</option>
                                            @foreach (\App\Models\ReviewerStatus::all() as $st)
                                                <option value="{{ $st->name }}">{{ strtoupper($st->name) }}</option>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                @php
                                    // Cek kelengkapan form untuk kontrol tombol
                                    $statusTerisi = !empty($rev_status_id);
                                    $catatanCukup = strlen(trim($notes ?? '')) >= 10;
                                    $bolehKirim = $statusTerisi && $catatanCukup && !$isReadOnly;
                                @endphp

                                {{-- Status Rekomendasi
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">
                                        Status Rekomendasi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model.live="rev_status_id"
                                        class="form-select {{ !$statusTerisi && !$isReadOnly ? 'border-danger' : '' }}"
                                        @if ($isReadOnly) disabled @endif>
                                        <option value="">-- Pilih Status --</option>
                                        @foreach (\App\Models\ReviewerStatus::all() as $st)
                                            <option value="{{ $st->id }}">{{ strtoupper($st->name) }}</option>
                                        @endforeach
                                    </select>
                                    {{-- Pesan jika belum dipilih --}}
                                    {{-- @if (!$statusTerisi && !$isReadOnly)
                                        <div class="d-flex align-items-center gap-1 mt-1">
                                            <i class="ph-warning-circle text-danger" style="font-size:.85rem;"></i>
                                            <small class="text-danger fw-semibold">
                                                Status rekomendasi wajib dipilih.
                                            </small>
                                        </div>
                                    @endif
                                </div> --}} 

                                {{-- Feedback / Komentar --}}
                                <div class="mb-3 flex-fill d-flex flex-column">
                                    <label class="form-label fw-bold small">
                                        Feedback / Komentar
                                        <span class="text-danger">*</span>
                                        <span class="text-muted fw-normal">(min. 10 karakter)</span>
                                    </label>
                                    <textarea wire:model.live="notes"
                                        class="form-control flex-fill {{ !$catatanCukup && !$isReadOnly ? 'border-warning' : '' }}" rows="8"
                                        placeholder="Tuliskan komentar atau catatan evaluasi Anda..." @if ($isReadOnly) readonly @endif
                                        style="{{ $isReadOnly ? 'background-color:#f8f9fa;' : '' }}"></textarea>
                                    {{-- Counter karakter --}}
                                    @if (!$isReadOnly)
                                        <div class="d-flex justify-content-between mt-1">
                                            <small
                                                class="{{ !$catatanCukup ? 'text-warning fw-semibold' : 'text-success fw-semibold' }}">
                                                @if (!$catatanCukup)
                                                    <i class="ph-warning me-1"></i>
                                                    Kurang {{ 10 - strlen(trim($notes ?? '')) }} karakter lagi
                                                @else
                                                    <i class="ph-check-circle me-1"></i> Catatan cukup
                                                @endif
                                            </small>
                                            <small class="text-muted">{{ strlen(trim($notes ?? '')) }} karakter</small>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tombol Kirim --}}
                                <div class="mt-auto">
                                    @if (!$isReadOnly)
                                        {{-- Ringkasan syarat jika belum terpenuhi --}}
                                        @if (!$bolehKirim)
                                            <div class="alert alert-warning border-0 py-2 px-3 small mb-3">
                                                <div class="fw-bold mb-1">
                                                    <i class="ph-lock me-1"></i> Tombol terkunci karena:
                                                </div>
                                                <ul class="mb-0 ps-3">
                                                    @if (!$statusTerisi)
                                                        <li>Status rekomendasi belum dipilih</li>
                                                    @endif
                                                    @if (!$catatanCukup)
                                                        <li>Catatan kurang dari 10 karakter</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif

                                        <button type="button" wire:click="submitReview" wire:loading.attr="disabled"
                                            @if (!$bolehKirim) disabled @endif
                                            class="btn w-100 rounded-pill py-2 shadow-sm fw-bold
                                                {{ $bolehKirim ? 'btn-primary' : 'btn-secondary' }}">
                                            <span wire:loading.remove wire:target="submitReview">
                                                <i class="ph-paper-plane-tilt me-2"></i> Kirim Penilaian
                                            </span>
                                            <span wire:loading wire:target="submitReview">
                                                <span class="spinner-border spinner-border-sm me-2"></span>
                                                Mengirim...
                                            </span>
                                        </button>
                                    @else
                                        <div class="alert alert-success border-0 py-3 text-center">
                                            <i class="ph-check-circle ph-lg d-block mb-1 text-success"></i>
                                            <span class="fw-bold small">Penilaian sudah terkirim secara final.</span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>{{-- /col-md-4 --}}

                    </div>{{-- /row --}}
                </div>{{-- /modal-body --}}
            @endif

        </div>
    </div>
</div>



