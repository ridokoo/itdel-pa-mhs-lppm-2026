<div>
    <div class="page-header border-0 mb-3">
        <h4 class="page-title mb-0 text-white">Riwayat & Undangan Anggota Usulan</h4>
    </div>

    <div class="row">
        @forelse($invitations as $invite)
            @php
                $statusId = $invite->member_status_id;
                $isPending = ($statusId == 1);
                // Cek apakah proposal ada untuk menghindari error
                $proposal = $invite->proposal;
            @endphp

            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm border-start border-start-width-3 {{ $statusId == 2 ? 'border-start-success opacity-80' : ($statusId == 3 ? 'border-start-danger opacity-60' : 'border-start-primary') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <!-- Gunakan ?-> untuk keamanan -->
                            <h6 class="fw-bold text-primary mb-0 pe-3">
                                {{ $proposal?->title ?? 'Judul Usulan Tidak Ditemukan' }}
                            </h6>

                            @if($statusId == 2)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Disetujui</span>
                            @elseif($statusId == 3)
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Ditolak</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Menunggu</span>
                            @endif
                        </div>

                        {{-- Tambah di bawah info skema, sebelum @if ($isPending) --}}
@if(!($invitation_id ?? null))
    <div class="mb-2">
        <a href="{{ url()->current() }}?id={{ $invite->id }}"
            class="btn btn-outline-primary btn-xs rounded-pill px-3">
            <i class="ph-arrow-square-out me-1"></i> Lihat Detail
        </a>
    </div>
@endif

                        <div class="small text-muted mb-3">
                            <!-- Tambahkan pengecekan null-safe ?-> -->
                            Ketua Pengusul: <strong>{{ $proposal?->leader?->name ?? 'Tidak Diketahui' }}</strong><br>
                            Skema: {{ $proposal?->schemeType?->name ?? 'Skema Tidak Tersedia' }}
                        </div>

                        @if ($isPending && $proposal)
                            <div class="d-flex gap-2">
                                <button wire:click="accept({{ $invite->id }})"
                                    class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="ph-check me-1"></i> Setujui
                                </button>
                                <button wire:click="reject({{ $invite->id }})"
                                    class="btn btn-light btn-sm rounded-pill px-3 text-danger">
                                    <i class="ph-x me-1"></i> Tolak
                                </button>
                            </div>
                        @else
                            <div class="text-muted small italic">
                                <i class="ph-clock-counter-clockwise me-1"></i>
                                Diproses pada {{ $invite->updated_at ? $invite->updated_at->format('d M Y H:i') : '-' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="ph-envelope-open ph-3x opacity-20 mb-2"></i>
                <p class="text-muted">Tidak ada riwayat undangan saat ini.</p>
            </div>
        @endforelse
    </div>
</div>



