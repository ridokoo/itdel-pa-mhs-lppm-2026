<div class="card border-0 shadow-none bg-transparent">

    {{-- 1. TAB NAVIGATION --}}
<div class="nav-tabs-wrapper mb-3">
    <ul class="nav nav-tabs nav-tabs-underline nav-justified border-bottom-0">
        <li class="nav-item">
            <a href="{{ $categoryId ? route('dosen.usulan.index', ['categoryId' => $categoryId]) : route('index') }}"
                class="nav-link text-muted text-nowrap">
                <i class="ph-house me-2"></i> Usulan
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active fw-bold text-primary">
                <i class="ph-file-text me-2"></i> Laporan Kemajuan
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ $categoryId ? route('dosen.laporan-akhir.index.category', ['categoryId' => $categoryId]) : route('dosen.laporan-akhir.index') }}"
                class="nav-link text-muted text-nowrap">
                <i class="ph-check-square-offset me-2"></i> Laporan Akhir
            </a>
        </li>
    </ul>
</div>

    {{-- 2. JUDUL --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    {{-- Kiri: Judul --}}
    <div>
        <h3 class="fw-bold mb-0">Daftar Laporan Kemajuan</h3>
        <p class="text-muted small mb-0">Kelola laporan kemajuan untuk proposal yang telah disetujui.</p>
    </div>

    {{-- Kanan: Filter Tahun --}}
    <div class="d-flex flex-column align-items-end">
        <label class="form-label small fw-bold mb-1">Tahun Pelaksanaan</label>
        <div class="input-group" style="width: 260px;">
            <span class="input-group-text bg-primary text-white border-primary">
                <i class="ph-funnel"></i>
            </span>
            <select class="form-select" wire:model.live="filterYear">
                <option value="all">-- Semua Tahun --</option>
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

    {{-- 4. TABEL --}}
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="text-center py-3" style="width:50px; white-space:nowrap">No.</th>
                        <th class="py-3" style="white-space:nowrap">Judul Proposal</th>
                        <th class="text-center py-3" style="white-space:nowrap">Skema</th>
                        <th class="text-center py-3" style="white-space:nowrap">Status Proposal</th>
                        <th class="text-center py-3" style="white-space:nowrap">Laporan Kemajuan</th>
                        <th class="py-3" style="white-space:nowrap">Komentar Reviewer</th>
                        <th class="text-center py-3" style="width:140px; white-space:nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $p)
                        @php
                            $laporan     = $p->progressReports->first();
                            $lastReview  = $laporan?->reviews->sortByDesc('created_at')->first();
                            $statusNama  = strtolower($laporan?->status?->name ?? '');
                            $isSubmitted = $laporan && $statusNama !== 'draft';
                            $isRevision  = str_contains($statusNama, 'revision');
                            $isApproved  = str_contains($statusNama, 'approved');
                        @endphp
                        <tr>
                            {{-- No --}}
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>

                            {{-- Judul --}}
                            <td>
                                <div class="fw-semibold text-primary lh-sm mb-1">{{ $p->title }}</div>
                                <div class="text-muted small">{{ $p->scope?->name ?? '-' }}</div>
                            </td>

                            {{-- Skema --}}
                            <td class="text-center">
                                <div class="small fw-bold">{{ $p->schemeType?->name ?? '-' }}</div>
                            </td>

                            {{-- Status Proposal --}}
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold" style="white-space:nowrap">
                                    {{ strtoupper($p->status?->name ?? '-') }}
                                </span>
                            </td>

                            {{-- Status Laporan Kemajuan --}}
                            <td class="text-center">
                                @if($laporan)
                                    @php
                                        $badgeClass = match(true) {
                                            $isApproved                            => 'bg-success text-white',
                                            $isRevision                            => 'bg-danger text-white',
                                            str_contains($statusNama, 'submitted') => 'bg-warning text-dark',
                                            default                                => 'bg-secondary bg-opacity-10 text-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-bold" style="white-space:nowrap">
                                        {{ strtoupper($laporan->status?->name ?? 'Draft') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill" style="white-space:nowrap">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>

                            {{-- Komentar Reviewer --}}
                            <td style="max-width:260px">
                                @if($lastReview)
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="mt-1 flex-shrink-0">
                                            @if($isApproved)
                                                <i class="ph-check-circle text-success fs-5"></i>
                                            @elseif($isRevision)
                                                <i class="ph-warning-circle text-danger fs-5"></i>
                                            @else
                                                <i class="ph-chat-circle text-muted fs-5"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">
                                                {{ $lastReview->reviewer?->name ?? 'Reviewer' }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px">
                                                {{ \Carbon\Carbon::parse($lastReview->created_at)->format('d M Y, H:i') }}
                                            </div>
                                            @if($lastReview->review_notes)
                                                <div class="mt-1 p-2 rounded-2 small {{ $isRevision ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">
                                                    {{ Str::limit($lastReview->review_notes, 120) }}
                                                </div>
                                                @if(strlen($lastReview->review_notes) > 120)
                                                    <button type="button"
                                                        class="btn btn-link btn-sm p-0 mt-1 text-muted"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalKomentar{{ $laporan->id }}">
                                                        Lihat selengkapnya →
                                                    </button>
                                                @endif
                                            @else
                                                <div class="text-muted small fst-italic mt-1">Tidak ada catatan.</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                @if($laporan)
                                    @if($isSubmitted && !$isRevision)
                                        <a href="{{ route('dosen.laporan-kemajuan.form', ['proposal_id' => $p->id, 'id_laporan' => $laporan->id, 'mode' => 'view']) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="ph-eye me-1"></i> Lihat
                                        </a>
                                    @elseif($isRevision)
                                        <a href="{{ route('dosen.laporan-kemajuan.form', ['proposal_id' => $p->id, 'id_laporan' => $laporan->id]) }}"
                                            class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold">
                                            <i class="ph-pencil-simple me-1"></i> Revisi
                                        </a>
                                    @else
                                        <a href="{{ route('dosen.laporan-kemajuan.form', ['proposal_id' => $p->id, 'id_laporan' => $laporan->id]) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="ph-pencil me-1"></i> Edit
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('dosen.laporan-kemajuan.form', ['proposal_id' => $p->id]) }}"
                                        class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                        <i class="ph-plus-circle me-1"></i> Buat
                                    </a>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal komentar lengkap --}}
                        @if($laporan && $lastReview && strlen($lastReview->review_notes ?? '') > 120)
                            <div class="modal fade" id="modalKomentar{{ $laporan->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0">
                                            <h6 class="modal-title fw-bold">
                                                Komentar Reviewer — {{ $lastReview->reviewer?->name }}
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-muted small mb-2">
                                                {{ \Carbon\Carbon::parse($lastReview->created_at)->format('d M Y, H:i') }}
                                            </div>
                                            <div class="p-3 rounded-3 {{ $isRevision ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">
                                                {{ $lastReview->review_notes }}
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="ph-files" style="font-size:3rem; opacity:.2; display:block; margin-bottom:8px"></i>
                                <h6 class="text-muted">Tidak Ada Proposal yang Disetujui</h6>
                                <p class="text-muted small mx-auto mb-3" style="max-width:400px">
                                    Hanya proposal dengan status <strong>Disetujui/Approved</strong> yang dapat mengisi laporan kemajuan.
                                </p>
                                @if($filterYear != 'all')
                                    <button wire:click="$set('filterYear', 'all')"
                                        class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                        Lihat Semua Tahun
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-3 py-2 border-top bg-light">
            <span class="text-muted small">Menampilkan {{ $proposals->count() }} laporan kemajuan</span>
        </div>
    </div>

</div>
