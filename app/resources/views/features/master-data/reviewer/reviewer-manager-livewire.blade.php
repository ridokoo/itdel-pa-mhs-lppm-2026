<div>
    {{-- TAMPILAN 1: DAFTAR PERIODE --}}
    @if ($viewMode == 'list_periods')
        <div class="page-header border-0 mb-3">
            <h4 class="page-title mb-0">Manajemen Reviewer</h4>
            <div class="text-muted">Pilih periode proposal untuk mengelola daftar penilai.</div>
        </div>

        <div class="row">
            @foreach ($periods as $p)
                @php
                    $now = now()->format('Y-m-d');
                    // Periode dianggap AKTIF jika is_active=true DAN tanggal hari ini masuk di rentang
                    $isWithinDate = $now >= $p->start_date && $now <= $p->end_date;
                    $isTrulyActive = $p->is_active && $isWithinDate;
                @endphp

                <div class="col-md-4 mb-3">
                    <div
                        class="card border-top border-top-width-3 {{ $isTrulyActive ? 'border-top-primary' : 'border-top-secondary opacity-75' }} shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="fw-bold mb-0 {{ $isTrulyActive ? 'text-primary' : 'text-muted' }}">
                                    {{ $p->year }}</h5>
                                @if ($isTrulyActive)
                                    <span class="badge bg-success bg-opacity-10 text-success ms-auto">AKTIF</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger ms-auto">NON-AKTIF</span>
                                @endif
                            </div>

                            <h6 class="fw-semibold small mb-1">{{ $p->name }}</h6>
                            <div class="small text-muted mb-3">
                                <i class="ph-calendar me-1"></i> {{ date('d M', strtotime($p->start_date)) }} -
                                {{ date('d M Y', strtotime($p->end_date)) }}
                            </div>

                            @if ($isTrulyActive)
                                {{-- TOMBOL AKTIF --}}
                                <button wire:click="selectPeriod({{ $p->id }})"
                                    class="btn btn-primary w-100 rounded-pill shadow-sm">
                                    Kelola Reviewer <i class="ph-arrow-right ms-2"></i>
                                </button>
                            @else
                                {{-- TOMBOL MATI (DISABLED) --}}
                                <button class="btn btn-secondary w-100 rounded-pill shadow-none cursor-not-allowed"
                                    disabled>
                                    <i class="ph-lock me-2"></i> Periode Terkunci
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- TAMPILAN 2: KELOLA DOSEN DALAM PERIODE --}}
    @else
        <div class="page-header border-0 mb-3">
            <div class="d-flex align-items-center">
                <button wire:click="backToPeriods" class="btn btn-light btn-icon rounded-pill me-3">
                    <i class="ph-arrow-left"></i>
                </button>
                <div>
                    <h4 class="page-title mb-0">Kelola Reviewer - {{ $selectedPeriod->year }}</h4>
                    <div class="text-muted small">{{ $selectedPeriod->name }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                {{-- Form Pencarian Dosen (Tetap seperti sebelumnya) --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <label class="form-label fw-bold">Cari Dosen (API CIS)</label>
                        <div class="form-control-feedback form-control-feedback-start mb-3">
                            <input type="text" class="form-control" placeholder="Ketik nama..."
                                wire:model.live.debounce.500ms="search">
                            <div class="form-control-feedback-icon"><i class="ph-magnifying-glass"></i></div>
                        </div>

                        @if (!empty($searchResults))
                            <div class="list-group shadow-lg">
                                @foreach ($searchResults as $res)
                                    @php $u = (array)$res; @endphp
                                    <button wire:click="assignAsReviewer({{ json_encode($u) }})"
                                        class="list-group-item list-group-item-action d-flex align-items-center">
                                        <div class="text-start">
                                            <div class="fw-bold small">{{ $u['name'] }}</div>
                                            <div class="fs-xs text-muted">{{ $u['email'] }}</div>
                                        </div>
                                        <i class="ph-plus-circle ms-auto text-primary"></i>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        {{-- KANAN: Daftar Reviewer Saat Ini --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Daftar Reviewer Aktif</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviewers as $rev)
                                <tr>
                                    <td><span class="fw-semibold">{{ $rev->name }}</span></td>
                                    <td>{{ $rev->email }}</td>
                                    <td class="text-center">
                                        <button wire:click="removeReviewer({{ $rev->id }})"
                                            class="btn btn-sm btn-light text-danger">
                                            <i class="ph-trash me-1"></i> Cabut Akses
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Belum ada reviewer yang ditunjuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>



