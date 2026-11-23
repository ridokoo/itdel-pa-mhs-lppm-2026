<div class="p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color:#0070C0;">Review Laporan Akhir</h3>
            <p class="text-muted mb-0">Laporan akhir yang menunggu persetujuan Anda.</p>
        </div>
        <select class="form-select form-select-sm" wire:model.live="filterStatus" style="width:180px;">
            <option value="submitted">Menunggu Review</option>
            <option value="all">Semua</option>
        </select>
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
                        <th class="text-center py-3">Status Laporan</th>
                        <th class="text-center py-3">Status Proposal</th>
                        <th class="text-center py-3">Diajukan</th>
                        <th class="text-center py-3" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanList as $l)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $l->proposal->title }}</div>
                                <div class="text-muted small">{{ $l->proposal->schemeType?->name ?? '-' }}</div>
                            </td>
                            <td class="text-center small">{{ $l->proposal->leader?->name ?? '-' }}</td>
                            <td class="text-center small">{{ $l->proposal->category?->name ?? '-' }}</td>
                            <td class="text-center fw-bold text-success">
                                Rp {{ number_format($l->total_spent_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @php
                                    $sn = strtolower($l->status?->name ?? '');
                                    $bc = match (true) {
                                        str_contains($sn, 'submitted') => 'bg-warning text-dark',
                                        str_contains($sn, 'approved') => 'bg-success text-white',
                                        str_contains($sn, 'revision') => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $bc }} rounded-pill px-3 py-2">
                                    {{ strtoupper($l->status?->name ?? '-') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $ps = strtolower($l->proposal->status?->name ?? '');
                                    $pbc = match (true) {
                                        str_contains($ps, 'selesai') => 'bg-success text-white',
                                        str_contains($ps, 'approved') => 'bg-primary text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $pbc }} rounded-pill px-3 py-2">
                                    {{ strtoupper($l->proposal->status?->name ?? '-') }}
                                </span>
                            </td>
                            <td class="text-center small text-muted">
                                {{ $l->submitted_at ? \Carbon\Carbon::parse($l->submitted_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reviewer.laporan-akhir.form', $l->id) }}"
                                    class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="ph-eye me-1"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ph-files d-block mb-3 text-muted" style="font-size:3rem;opacity:0.3;"></i>
                                <h6 class="text-muted">Tidak ada laporan akhir yang perlu direview</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



