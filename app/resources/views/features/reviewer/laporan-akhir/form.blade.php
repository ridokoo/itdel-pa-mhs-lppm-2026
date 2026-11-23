<div class="p-4">
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#0070C0;">Review Laporan Akhir</h5>
                    <p class="text-muted mb-0 small">{{ $laporan->proposal->title }}</p>
                </div>
                <a href="{{ route('reviewer.laporan-akhir.index') }}"
                    class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Kolom Kiri: Detail --}}
        <div class="col-lg-8">

            {{-- Info Proposal --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0"><i class="ph-info me-2"></i> Informasi Proposal</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Ketua Peneliti</div>
                            <div class="fw-semibold">{{ $laporan->proposal->leader?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Skema</div>
                            <div class="fw-semibold">{{ $laporan->proposal->schemeType?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Total Anggaran</div>
                            <div class="fw-semibold">Rp {{ number_format($laporan->proposal->total_budget, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Total Terpakai</div>
                            <div class="fw-semibold text-success">Rp {{ number_format($laporan->total_spent_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Persentase</div>
                            @php
                                $pct = $laporan->proposal->total_budget > 0
                                    ? round(($laporan->total_spent_amount / $laporan->proposal->total_budget) * 100, 1)
                                    : 0;
                            @endphp
                            <div class="fw-semibold {{ $pct > 100 ? 'text-danger' : 'text-primary' }}">{{ $pct }}%</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status Proposal Saat Ini</div>
                            @php
                                $ps = strtolower($laporan->proposal->status?->name ?? '');
                                $pbc = match(true) {
                                    str_contains($ps, 'selesai')  => 'bg-success text-white',
                                    str_contains($ps, 'approved') => 'bg-primary text-white',
                                    default => 'bg-secondary text-white',
                                };
                            @endphp
                            <span class="badge {{ $pbc }} rounded-pill px-3 py-1 mt-1">
                                {{ strtoupper($laporan->proposal->status?->name ?? '-') }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Tanggal Diajukan</div>
                            <div class="fw-semibold">
                                {{ $laporan->submitted_at ? \Carbon\Carbon::parse($laporan->submitted_at)->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Realisasi Anggaran --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0"><i class="ph-money me-2"></i> Realisasi Anggaran</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr class="small text-uppercase text-muted">
                                    <th>Item</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Rencana</th>
                                    <th class="text-end">Realisasi</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan->budgets as $b)
                                @php
                                    $plan  = $b->budgetItem->total ?? 0;
                                    $spent = $b->amount_spent;
                                    $pctB  = $plan > 0 ? round(($spent / $plan) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $b->budgetItem->item_name }}</td>
                                    <td><span class="badge bg-secondary">{{ $b->budgetItem->expenseCategory?->name }}</span></td>
                                    <td class="text-end">Rp {{ number_format($plan, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($spent, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="{{ $pctB > 100 ? 'text-danger' : 'text-success' }} fw-bold">{{ $pctB }}%</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Capaian Luaran --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0"><i class="ph-target me-2"></i> Capaian Luaran Akhir</h6>
                </div>
                <div class="card-body">
                    @forelse($laporan->outputs as $out)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary">{{ $out->outputTarget?->outputTargetType?->name ?? '-' }}</span>
                            @if($out->outputTargetStatus)
                                <span class="badge bg-success">{{ $out->outputTargetStatus->name }}</span>
                            @endif
                        </div>
                        <div class="text-muted small mb-2">{{ $out->achievement_description ?? '-' }}</div>
                        @if($out->evidence_file)
                            <a href="{{ Storage::url($out->evidence_file) }}" target="_blank"
                                class="btn btn-sm btn-outline-success rounded-pill px-3" style="font-size:12px;">
                                <i class="ph-file me-1"></i> Lihat Bukti
                            </a>
                        @endif
                    </div>
                    @empty
                        <div class="text-muted small">Tidak ada luaran.</div>
                    @endforelse
                </div>
            </div>

            {{-- Rencana Integrasi --}}
            @if($laporan->integration_plan)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0"><i class="ph-lightbulb me-2"></i> Rencana Integrasi Hasil</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $laporan->integration_plan }}</p>
                </div>
            </div>
            @endif

            {{-- Dokumen --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0"><i class="ph-files me-2"></i> Dokumen Pendukung</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 text-center">
                            <div class="text-muted small mb-2">Bukti Pengeluaran (100%)</div>
                            @if($laporan->expense_proof_file)
                                <a href="{{ Storage::url($laporan->expense_proof_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill w-100">
                                    <i class="ph-eye me-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-danger small">❌ Belum ada</span>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-muted small mb-2">Logbook Lengkap</div>
                            @if($laporan->logbook_file)
                                <a href="{{ Storage::url($laporan->logbook_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill w-100">
                                    <i class="ph-eye me-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-danger small">❌ Belum ada</span>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-muted small mb-2">Dokumen Laporan Akhir</div>
                            @if($laporan->final_report_file)
                                <a href="{{ Storage::url($laporan->final_report_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill w-100">
                                    <i class="ph-eye me-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-danger small">❌ Belum ada</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Form Review --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0"><i class="ph-seal-check me-2"></i> Keputusan Review</h6>
                </div>
                <div class="card-body">

                    {{-- Status saat ini --}}
                    <div class="mb-3">
                        <div class="text-muted small mb-1">Status Laporan</div>
                        @php
                            $sn = strtolower($laporan->status?->name ?? '');
                            $bc = match(true) {
                                str_contains($sn, 'submitted') => 'bg-warning text-dark',
                                str_contains($sn, 'approved')  => 'bg-success text-white',
                                str_contains($sn, 'revision')  => 'bg-danger text-white',
                                default => 'bg-secondary text-white',
                            };
                        @endphp
                        <span class="badge {{ $bc }} rounded-pill px-3 py-2">
                            {{ strtoupper($laporan->status?->name ?? '-') }}
                        </span>
                    </div>

                    @if(strtolower($laporan->status?->name ?? '') === 'submitted')

                        {{-- Catatan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">
                                Catatan Review
                                <span class="text-muted">(wajib jika revisi)</span>
                            </label>
                            <textarea wire:model="review_notes" class="form-control form-control-sm" rows="5"
                                placeholder="Tuliskan catatan atau alasan revisi..."></textarea>
                            @error('review_notes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="d-grid gap-2">
                            {{-- ✅ Setujui → laporan approved + proposal Selesai --}}
                            <button
                                wire:click="submitReview('approved')"
                                wire:loading.attr="disabled"
                                wire:confirm="Setujui laporan akhir ini? Proposal akan ditandai sebagai SELESAI."
                                class="btn btn-success rounded-pill fw-bold">
                                <span wire:loading.remove wire:target="submitReview">
                                    <i class="ph-check-circle me-1"></i> Setujui & Tandai Selesai
                                </span>
                                <span wire:loading wire:target="submitReview">
                                    <span class="spinner-border spinner-border-sm me-1"></span> Memproses...
                                </span>
                            </button>

                            {{-- ⚠ Minta Revisi --}}
                            <button
                                wire:click="submitReview('revision')"
                                wire:loading.attr="disabled"
                                wire:confirm="Minta revisi untuk laporan akhir ini?"
                                class="btn btn-danger rounded-pill fw-bold">
                                <span wire:loading.remove wire:target="submitReview">
                                    <i class="ph-arrow-counter-clockwise me-1"></i> Minta Revisi
                                </span>
                                <span wire:loading wire:target="submitReview">
                                    <span class="spinner-border spinner-border-sm me-1"></span> Memproses...
                                </span>
                            </button>
                        </div>

                        {{-- Info efek approve --}}
                        <div class="alert alert-info rounded-3 mt-3 small">
                            <i class="ph-info me-1"></i>
                            Menyetujui laporan akhir akan otomatis mengubah status proposal menjadi
                            <strong>SELESAI</strong>.
                        </div>

                    @else
                        <div class="alert alert-secondary rounded-3 small">
                            <i class="ph-info me-2"></i>
                            Laporan ini sudah direview sebelumnya.
                        </div>

                        {{-- Riwayat Review --}}
                        @if($laporan->reviews->count() > 0)
                        <div class="mt-3">
                            <div class="text-muted small fw-semibold mb-2">Riwayat Review</div>
                            @foreach($laporan->reviews->sortByDesc('created_at') as $review)
                            <div class="border rounded-3 p-2 mb-2 small">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">{{ $review->reviewer?->name ?? '-' }}</span>
                                    <span class="text-muted" style="font-size:11px;">
                                        {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                                    </span>
                                </div>
                                @if($review->review_notes)
                                    <div class="text-muted mt-1">{{ $review->review_notes }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>



