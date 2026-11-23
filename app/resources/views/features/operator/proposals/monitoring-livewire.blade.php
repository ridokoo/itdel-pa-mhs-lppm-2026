<div>
    <div class="page-header border-0 mb-3">
        <h4 class="page-title mb-0">Daftar Usulan Proposal</h4>
    </div>

    {{-- Filter Pencarian --}}
    <div class="card card-body border-0 shadow-sm mb-3">
        <div class="form-control-feedback form-control-feedback-start">
            <input type="text" class="form-control" placeholder="Cari Judul atau Nama Pengusul..."
                wire:model.live="search">
            <div class="form-control-feedback-icon"><i class="ph-magnifying-glass"></i></div>
        </div>
    </div>

    {{-- Tabel Monitoring --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th>Info Proposal</th>
                        <th>Ketua Pengusul</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proposals as $p)
                        <tr>
                            <td>
                                <div class="fw-bold text-primary">{{ str($p->title)->limit(60) }}</div>
                                <div class="fs-xs text-muted">{{ $p->schemeType->name }} | TKT {{ $p->target_tkt }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->leader->name }}</div>
                                <div class="fs-xs text-muted">{{ $p->leader->email }}</div>
                            </td>
                            <td class="text-center">
                                {{-- Warna Badge Dinamis Berdasarkan Status --}}
                                <span
                                    class="badge bg-opacity-10
                                    {{ str_contains(strtolower($p->status->name), 'setuju') ? 'bg-success text-success' : 'bg-primary text-primary' }}">
                                    {{ strtoupper($p->status->name) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button wire:click="showDetail({{ $p->id }})" class="btn btn-sm btn-indigo">
                                    <i class="ph-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada usulan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer px-3">{{ $proposals->links() }}</div>
    </div>

    @include('features.operator.proposals.modals.modal-detail')
</div>



