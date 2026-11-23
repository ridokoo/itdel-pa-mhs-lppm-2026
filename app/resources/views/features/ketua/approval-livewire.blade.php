<div>
    <div class="page-header border-0 mb-3">
        <h4 class="page-title mb-0">
            <i class="ph-gavel me-2 text-warning"></i>Persetujuan Final LPPM
        </h4>
        <div class="text-muted small">Tinjau rekomendasi reviewer dan tentukan keputusan akhir usulan.</div>
    </div>

    {{-- TAB NAVIGASI --}}
    <ul class="nav nav-tabs nav-tabs-underline border-bottom mb-4">
        <li class="nav-item">
            <a href="#" wire:click.prevent="$set('activeTab', 'pending')"
                class="nav-link {{ $activeTab === 'pending' ? 'active fw-bold text-primary' : 'text-muted' }}">
                <i class="ph-clock me-2"></i> Menunggu Keputusan
                @if ($proposals->count() > 0)
                    <span class="badge bg-danger ms-1">{{ $proposals->count() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="#" wire:click.prevent="$set('activeTab', 'history')"
                class="nav-link {{ $activeTab === 'history' ? 'active fw-bold text-primary' : 'text-muted' }}">
                <i class="ph-clock-counter-clockwise me-2"></i> Riwayat Keputusan
                @if ($history->count() > 0)
                    <span class="badge bg-secondary ms-1">{{ $history->count() }}</span>
                @endif
            </a>
        </li>
    </ul>

    {{-- TAB: MENUNGGU KEPUTUSAN --}}
    @if ($activeTab === 'pending')
        <div class="row">
            @forelse($proposals as $p)
                <div class="col-md-12 mb-3">
                    <div class="card border-0 shadow-sm border-start border-start-width-3 border-start-warning">
                        <div class="card-body d-sm-flex align-items-sm-center">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $p->title }}</h6>
                                <div class="small text-muted">
                                    Ketua: <b>{{ $p->leader?->name }}</b> |
                                    Skema: {{ $p->schemeType?->name ?? '-' }} |
                                    Diperbarui: {{ $p->updated_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="ms-sm-3 mt-3 mt-sm-0">
                                <span class="badge bg-warning text-dark me-2">Menunggu</span>
                                <button wire:click="selectProposal({{ $p->id }})"
                                    class="btn btn-primary rounded-pill px-4 shadow-sm">
                                    <i class="ph-gavel me-2"></i> Putuskan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="ph-check-circle ph-4x opacity-20 d-block mb-2"></i>
                    <h5>Semua usulan sudah diputuskan.</h5>
                </div>
            @endforelse
        </div>
    @endif

    {{-- TAB: RIWAYAT KEPUTUSAN --}}
    @if ($activeTab === 'history')
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Judul Usulan</th>
                            <th>Ketua Pengusul</th>
                            <th>Skema</th>
                            <th class="text-center">Keputusan</th>
                            <th>Catatan</th>
                            <th>Diperbarui</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($history as $p)
                            @php
                                $statusName = strtoupper($p->status?->name ?? '');
                                $badgeColor = match ($statusName) {
                                    'APPROVED' => 'bg-success',
                                    'REJECTED' => 'bg-danger',
                                    'REVISION' => 'bg-warning text-dark',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold text-primary">{{ $p->title }}</div>
                                </td>
                                <td>{{ $p->leader?->name ?? '-' }}</td>
                                <td>{{ $p->schemeType?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeColor }} px-3">{{ $statusName }}</span>
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
                                <td colspan="7" class="text-center py-4 text-muted italic">
                                    Belum ada riwayat keputusan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- MODAL FORM KEPUTUSAN --}}
    @include('features.ketua.modals.decision-form')
</div>



