<div wire:ignore.self class="modal fade" id="modal_detail_usulan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white border-0">
                <h6 class="modal-title">Detail Usulan Proposal</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            @if ($selectedProposal)
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="text-muted fs-xs text-uppercase fw-bold">Judul Usulan</label>
                        <h5 class="fw-bold text-indigo">{{ $selectedProposal->title }}</h5>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="text-muted fs-xs text-uppercase fw-bold">Skema Penelitian</label>
                            <p class="fw-semibold">{{ $selectedProposal->schemeType->name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted fs-xs text-uppercase fw-bold">Ruang Lingkup</label>
                            <p class="fw-semibold">{{ $selectedProposal->scope->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="text-muted fs-xs text-uppercase fw-bold">Target TKT</label>
                            <p class="fw-semibold">Level {{ $selectedProposal->target_tkt }}</p>
                        </div>
                        <div class="col-sm-4">
                            <label class="text-muted fs-xs text-uppercase fw-bold">Durasi</label>
                            <p class="fw-semibold">{{ $selectedProposal->duration_months }} Bulan</p>
                        </div>
                        <div class="col-sm-4">
                            <label class="text-muted fs-xs text-uppercase fw-bold">Total Anggaran</label>
                            <p class="fw-semibold text-success">Rp
                                {{ number_format($selectedProposal->total_budget, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded">
                        <label class="text-muted fs-xs text-uppercase fw-bold">Ringkasan / Abstrak</label>
                        <p class="mb-0 small text-justify">{{ $selectedProposal->summary ?? 'Tidak ada ringkasan.' }}
                        </p>
                    </div>

                    <div class="d-flex align-items-center p-2 border rounded border-dashed">
                        <i class="ph-file-pdf ph-2x text-danger me-2"></i>
                        <div class="flex-fill">
                            <div class="fw-bold small">Dokumen Proposal</div>
                            <div class="fs-xs text-muted">File:
                                {{ $selectedProposal->proposal_file ?? 'Belum diunggah' }}</div>
                        </div>
                        @if ($selectedProposal->proposal_file)
                            <a href="#" class="btn btn-sm btn-light">Unduh</a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



