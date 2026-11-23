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
                <a href="{{ $categoryId ? route('dosen.laporan-kemajuan.index', ['categoryId' => $categoryId]) : '#' }}"
                    class="nav-link text-muted text-nowrap">
                    <i class="ph-file-text me-2"></i> Laporan Kemajuan
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active fw-bold text-primary">
                    <i class="ph-check-square-offset me-2"></i> Laporan Akhir
                </a>
            </li>
        </ul>
    </div>

    {{-- 2. TOOLBAR --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        {{-- Kiri: Judul --}}
        <div>
            <h3 class="fw-bold mb-0">Daftar Laporan Akhir</h3>
            <p class="text-muted small mb-0">Kelola laporan akhir untuk proposal yang telah disetujui.</p>
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

    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 3. TABEL --}}
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="text-center py-3" style="width:50px; white-space:nowrap">No.</th>
                        <th class="py-3" style="white-space:nowrap">Judul Proposal</th>
                        <th class="text-center py-3" style="white-space:nowrap">Skema</th>
                        <th class="text-center py-3" style="white-space:nowrap">Tahun</th>
                        <th class="text-center py-3" style="white-space:nowrap">Status Laporan Akhir</th>
                        <th class="text-center py-3" style="width:140px; white-space:nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($proposals->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="ph-files" style="font-size:3rem; opacity:.2; display:block; margin-bottom:8px"></i>
                                <h6 class="text-muted">Belum Ada Proposal yang Memenuhi Syarat</h6>
                                <p class="text-muted small mx-auto mb-0" style="max-width:400px">
                                    Laporan kemajuan perlu disetujui terlebih dahulu.
                                </p>
                            </td>
                        </tr>
                    @else
                        @foreach ($proposals as $i => $proposal)
                            @php
                                $report     = $proposal->finalReport;
                                $statusNama = strtolower($report?->status?->name ?? '');
                                $isApproved = str_contains($statusNama, 'approved') ||
                                              str_contains($statusNama, 'disetujui') ||
                                              str_contains($statusNama, 'selesai');
                                $isRevision = str_contains($statusNama, 'revisi') ||
                                              str_contains($statusNama, 'revision');

                                $badgeClass = match(true) {
                                    $isApproved => 'bg-success text-white',
                                    $isRevision => 'bg-danger text-white',
                                    default     => 'bg-secondary bg-opacity-10 text-secondary',
                                };
                            @endphp
                            <tr>
                                {{-- No --}}
                                <td class="text-center text-muted">{{ $i + 1 }}</td>

                                {{-- Judul --}}
                                <td>
                                    <div class="fw-semibold text-primary lh-sm mb-1">{{ $proposal->title }}</div>
                                    <div class="text-muted small">{{ $proposal->scope?->name ?? '-' }}</div>
                                </td>

                                {{-- Skema --}}
                                <td class="text-center">
                                    <div class="small fw-bold">{{ $proposal->schemeType?->name ?? '-' }}</div>
                                </td>

                                {{-- Tahun --}}
                                <td class="text-center">
                                    <span class="small">{{ $proposal->created_at?->format('Y') }}</span>
                                </td>

                                {{-- Status Laporan Akhir --}}
                                <td class="text-center">
                                    @if($report)
                                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-bold" style="white-space:nowrap">
                                            {{ strtoupper($report->status?->name ?? 'Draft') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill" style="white-space:nowrap">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    @if($report)
                                        @if($isApproved)
                                            <a href="{{ route('dosen.laporan-akhir.edit', [$proposal->id, $report->id, 'mode' => 'view']) }}"
                                                class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="ph-eye me-1"></i> Lihat
                                            </a>
                                        @else
                                            <a href="{{ route('dosen.laporan-akhir.edit', [$proposal->id, $report->id]) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="ph-pencil me-1"></i> Edit
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('dosen.laporan-akhir.create', $proposal->id) }}"
                                            class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                            <i class="ph-plus-circle me-1"></i> Buat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="px-3 py-2 border-top bg-light">
            <span class="text-muted small">Menampilkan {{ $proposals->count() }} laporan akhir</span>
        </div>
    </div>

</div>
