<div class="p-4">
    <style>
        .text-reviewer-blue { color: #0070C0 !important; }
        .bg-reviewer-blue { background-color: #0070C0 !important; color: white; }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-reviewer-blue">Daftar Laporan Kemajuan</h3>
            <p class="text-muted mb-0">Laporan yang menunggu review Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" wire:model.live="filterStatus" style="width:180px;">
                <option value="submitted">Menunggu Review</option>
                <option value="all">Semua</option>
            </select>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="text-center py-3" style="width:50px;">No.</th>
                        <th class="py-3">Judul Proposal</th>
                        <th class="text-center py-3">Ketua</th>
                        <th class="text-center py-3">Kategori</th>
                        <th class="text-center py-3">Total Terpakai</th>
                        <th class="text-center py-3">Status</th>
                        <th class="text-center py-3">Diajukan</th>
                        <th class="text-center py-3" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanList as $l)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $l->proposal?->title ?? '-' }}</div>
<div class="text-muted small">{{ $l->proposal?->schemeType?->name ?? '-' }}</div>
                        </td>
                        <td class="text-center small">{{ $l->proposal?->leader?->name ?? '-' }}</td>
<td class="text-center small">{{ $l->proposal?->category?->name ?? '-' }}</td>
                        <td class="text-center">
                            <span class="fw-bold text-success">
                                Rp {{ number_format($l->total_spent_amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $sName = strtolower($l->status?->name ?? '');
                                $badge = match(true) {
                                    str_contains($sName, 'submitted') => 'bg-warning text-dark',
                                    str_contains($sName, 'approved')  => 'bg-success text-white',
                                    str_contains($sName, 'revision')  => 'bg-danger text-white',
                                    default => 'bg-secondary text-white',
                                };
                            @endphp
                            <span class="badge {{ $badge }} rounded-pill px-3 py-2">
                                {{ strtoupper($l->status?->name ?? '-') }}
                            </span>
                        </td>
                        <td class="text-center small text-muted">
                            {{ $l->submitted_at ? \Carbon\Carbon::parse($l->submitted_at)->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('reviewer.laporan.form', $l->id) }}"
                                class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="ph-eye me-1"></i> Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="ph-files d-block mb-3 text-muted" style="font-size:3rem;opacity:0.3;"></i>
                            <h6 class="text-muted">Tidak ada laporan yang perlu direview</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


