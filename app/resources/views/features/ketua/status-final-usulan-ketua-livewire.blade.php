<div>
    {{-- ================= HEADER ================= --}}
    <div class="page-header border-0 mb-3">
        <h4 class="page-title mb-0">
            <i class="ph-seal-check me-2 text-primary"></i>Status Final Usulan
        </h4>
        <div class="text-muted small">Tentukan status final untuk setiap usulan yang telah melalui proses review.</div>
    </div>

    {{-- ================= TAB NAVIGASI ================= --}}
    <ul class="nav nav-tabs nav-tabs-underline border-bottom mb-4">
        <li class="nav-item">
            <a href="#" wire:click.prevent="$set('activeTab', 'pending')"
                class="nav-link {{ $activeTab === 'pending' ? 'active fw-bold text-primary' : 'text-muted' }}">
                <i class="ph-clock me-2"></i> Menunggu Status Final
                @if($proposals->count() > 0)
                    <span class="badge bg-danger ms-1">{{ $proposals->count() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="#" wire:click.prevent="$set('activeTab', 'history')"
                class="nav-link {{ $activeTab === 'history' ? 'active fw-bold text-primary' : 'text-muted' }}">
                <i class="ph-clock-counter-clockwise me-2"></i> Riwayat Status Final
                @if($history->count() > 0)
                    <span class="badge bg-secondary ms-1">{{ $history->count() }}</span>
                @endif
            </a>
        </li>
    </ul>

    {{-- ================= TAB: MENUNGGU ================= --}}
    @if($activeTab === 'pending')
        <div class="row">
            @forelse($proposals as $p)
                <div class="col-md-12 mb-3">
                    <div class="card border-0 shadow-sm border-start border-start-width-3 border-start-primary">
                        <div class="card-body d-sm-flex align-items-sm-center">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $p->title }}</h6>
                                <div class="small text-muted">
                                    Ketua: <b>{{ $p->leader?->name ?? '-' }}</b> |
                                    Skema: {{ $p->schemeType?->name ?? '-' }} |
                                    Diperbarui: {{ $p->updated_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="ms-sm-3 mt-3 mt-sm-0">
                                <span class="badge bg-primary me-2">Belum Ada Status Final</span>
                                <button wire:click="selectProposal({{ $p->id }})"
                                    class="btn btn-primary rounded-pill px-4 shadow-sm">
                                    <i class="ph-seal-check me-2"></i> Tetapkan Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="ph-check-circle ph-4x opacity-20 d-block mb-2"></i>
                    <h5>Semua usulan sudah memiliki status final.</h5>
                </div>
            @endforelse
        </div>
    @endif

    {{-- ================= TAB: RIWAYAT ================= --}}
    @if($activeTab === 'history')
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Judul Usulan</th>
                            <th>Ketua Pengusul</th>
                            <th>Skema</th>
                            <th class="text-center">Status Final</th>
                            <th>Catatan</th>
                            <th>Diperbarui</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($history as $p)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-primary">{{ $p->title }}</div>
                                </td>
                                <td>{{ $p->leader?->name ?? '-' }}</td>
                                <td>{{ $p->schemeType?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success px-3">
                                        {{ strtoupper($p->statusFinalResult?->name ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small" title="{{ $p->decision_note }}">
                                        {{ Str::limit($p->decision_note, 50) ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $p->updated_at->diffForHumans() }}</td>
                                <td class="text-center">
                                    <button wire:click="selectProposal({{ $p->id }})"
                                        class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="ph-eye me-1"></i> Lihat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada riwayat status final.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ================= MODAL TETAPKAN STATUS ================= --}}
    @if($showModal && $selectedProposal)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1050;
                    display:flex; align-items:center; justify-content:center;">
            <div style="background:#fff; border-radius:16px; width:100%; max-width:520px;
                        box-shadow:0 20px 60px rgba(0,0,0,0.15); padding:1.75rem;">

                {{-- Header Modal --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="ph-seal-check me-2 text-primary"></i> Tetapkan Status Final
                    </h5>
                    <button wire:click="closeModal" class="btn btn-light btn-sm">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>

                {{-- Info Proposal --}}
                <div class="mb-4 p-3 rounded-3" style="background:#f1f5f9;">
                    <div class="text-muted mb-1" style="font-size:12px;">Judul Proposal</div>
                    <div class="fw-semibold">{{ $selectedProposal->title }}</div>
                    <div class="text-muted small mt-1">
                        Ketua: {{ $selectedProposal->leader?->name ?? '-' }} |
                        Skema: {{ $selectedProposal->schemeType?->name ?? '-' }}
                    </div>
                </div>

                {{-- Pilih Status --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Status Final <span class="text-danger">*</span>
                    </label>
                    <select wire:model="selectedStatusId"
                        class="form-select @error('selectedStatusId') is-invalid @enderror"
                        style="border-radius:10px; border:1px solid #d1d5db;">
                        <option value="">-- Pilih Status Final --</option>
                        @foreach($statusOptions as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedStatusId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Catatan <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <textarea wire:model="catatan"
                        class="form-control"
                        rows="3"
                        style="border-radius:10px; border:1px solid #d1d5db;"
                        placeholder="Tambahkan catatan untuk pengusul..."></textarea>
                </div>

                {{-- Footer Modal --}}
                <div class="border-top pt-3 d-flex justify-content-end gap-2">
                    <button wire:click="closeModal" class="btn btn-light px-4"
                        style="border-radius:10px; font-weight:600;">
                        Batal
                    </button>
                    <button wire:click="confirm" class="btn btn-primary px-4 shadow-sm"
                        style="border-radius:10px; font-weight:600;">
                        <i class="ph-bold ph-seal-check me-2"></i> Simpan Status Final
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>



