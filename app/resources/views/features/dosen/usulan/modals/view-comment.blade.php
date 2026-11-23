<div wire:ignore.self class="modal fade" id="modal_view_comment" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white border-0">
                <h6 class="modal-title">
                    @if ($commentType === 'lppm')
                        <i class="ph-user-gear me-2"></i> Catatan Keputusan LPPM
                    @else
                        <i class="ph-users-four me-2"></i> Catatan Reviewer
                    @endif
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            @if ($viewedProposal)
                <div class="modal-body">
                    {{-- TAMPILKAN BERDASARKAN TIPE KOMENTAR --}}
                    @if ($commentType === 'lppm')
                        {{-- KOMENTAR LPPM --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 text-info lh-1 rounded-pill p-3 me-3">
                                <i class="ph-user-gear ph-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Judul Usulan</h6>
                                <small class="text-muted">{{ $viewedProposal->title }}</small>
                            </div>
                        </div>
                        <div class="bg-light p-4 rounded">
                            <h6 class="fw-bold mb-3">Komentar dari Ketua LPPM:</h6>
                            @if ($viewedProposal->decision_note)
                                <div class="lh-lg">
                                    {!! nl2br(e($viewedProposal->decision_note)) !!}
                                </div>
                            @else
                                <div class="alert alert-info border-0 mb-0">
                                    <i class="ph-info-circle me-2"></i>
                                    Belum ada komentar dari LPPM.
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- KOMENTAR REVIEWER --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-indigo bg-opacity-10 text-indigo lh-1 rounded-pill p-3 me-3">
                                <i class="ph-users-four ph-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Judul Usulan</h6>
                                <small class="text-muted">{{ $viewedProposal->title }}</small>
                            </div>
                        </div>
                        <div class="bg-light p-4 rounded">
                            <h6 class="fw-bold mb-3">Catatan dari Reviewer:</h6>
                            @if ($viewedProposal->reviewers->count() > 0)
                                @foreach ($viewedProposal->reviewers as $reviewer)
                                    @if ($reviewer->notes)
                                        <div class="mb-3 p-3 bg-white rounded border">
                                            <div class="lh-lg">
                                                {!! nl2br(e($reviewer->notes)) !!}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if ($viewedProposal->reviewers->whereNotNull('notes')->count() == 0)
                                    <div class="alert alert-info border-0 mb-0">
                                        <i class="ph-info-circle me-2"></i>
                                        Belum ada catatan dari Reviewer.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info border-0 mb-0">
                                    <i class="ph-info-circle me-2"></i>
                                    Belum ada reviewer yang ditugaskan.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



