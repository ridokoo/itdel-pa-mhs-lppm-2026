<div>
    <div class="page-header border-0 mb-3 text-white">
        <h4 class="page-title mb-0">Penugasan Reviewer (Plotting)</h4>
        <div class="text-muted small">Hubungkan usulan masuk dengan dosen pemeriksa yang sesuai kompetensi.</div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="form-control-feedback form-control-feedback-start">
                <input type="text" class="form-control" placeholder="Cari judul usulan..."
                    wire:model.live="search">
                <div class="form-control-feedback-icon"><i class="ph-magnifying-glass"></i></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th style="width: 45%;">Judul Usulan</th>
                        <th>Reviewer Ditugaskan</th>
                        <th class="text-center">Aksi Plot</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proposals as $p)
                        @php
                            // {{--
                            //     Kumpulkan semua ID yang tidak boleh jadi reviewer:
                            //     ketua + seluruh anggota tim usulan ini
                            // --}}
                            $excludedIds = $p->proposalMembers
                                ->pluck('user_id')
                                ->push($p->leader_id)
                                ->unique()
                                ->toArray();

                            $eligibleReviewers = $availableReviewers->whereNotIn('id', $excludedIds);
                        @endphp

                        <tr wire:key="proposal-{{ $p->id }}">
                            {{-- Judul & Info Tim --}}
                            <td>
                                <div class="fw-bold text-primary">{{ $p->title }}</div>
                                <div class="small text-muted">
                                    <i class="ph-user me-1"></i>Ketua: {{ $p->leader->name }}
                                </div>
                                @if ($p->proposalMembers->count() > 0)
                                    <div class="small text-muted mt-1">
                                        <i class="ph-users me-1"></i>Anggota:
                                        {{ $p->proposalMembers->map(fn($m) => $m->user->name)->join(', ') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Reviewer yang sudah ditugaskan --}}
                            <td>
                                @forelse ($p->reviewers as $rev)
                                    <div class="badge bg-indigo bg-opacity-10 text-indigo d-flex align-items-center mb-1 p-2">
                                        <span class="flex-grow-1 text-start">{{ $rev->user->name }}</span>
                                        <i class="ph-x cursor-pointer text-danger ms-2"
                                            wire:click="removeAssignment({{ $rev->id }})"></i>
                                    </div>
                                @empty
                                    <span class="text-muted italic small">Belum ditunjuk</span>
                                @endforelse
                            </td>

                            {{-- Aksi plot --}}
                            <td class="text-center">
                                @if ($eligibleReviewers->isEmpty())
                                    <span class="badge bg-warning text-dark small">
                                        <i class="ph-warning me-1"></i> Semua reviewer adalah anggota tim ini
                                    </span>
                                @else
                                    <div class="input-group input-group-sm">
                                        <select class="form-select" wire:model="reviewer_id">
                                            <option value="">-- Pilih Reviewer --</option>
                                            @foreach ($eligibleReviewers as $r)
                                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary"
                                            wire:click="assign({{ $p->id }})">
                                            Plot
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="ph-file-search ph-3x opacity-20 d-block mb-2"></i>
                                Tidak ada usulan yang perlu ditinjau.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



