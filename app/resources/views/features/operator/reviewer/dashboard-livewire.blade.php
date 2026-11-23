<div>
    <div class="page-header border-0 mb-3 text-white">
        <h4 class="page-title mb-0"><i class="ph-signature me-2"></i>Tugas Penilaian Usulan</h4>
    </div>

    <div class="row">
        @forelse($myTasks as $task)
            <div class="col-md-12 mb-3">
                <div
                    class="card border-0 shadow-sm border-start border-start-width-3 {{ $task->completed_at ? 'border-start-success' : 'border-start-warning' }}">
                    <div class="card-body d-sm-flex align-items-sm-center">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-primary mb-1">{{ $task->proposal->title }}</h6>
                            <div class="text-muted small">
                                Pengusul: <b>{{ $task->proposal->leader->name }}</b> |
                                Skema: {{ $task->proposal->schemeType->name }}
                            </div>
                        </div>

                        <div class="ms-sm-3 mt-3 mt-sm-0 text-center" style="min-width: 150px;">
                            @if ($task->completed_at)
                                {{-- TAMPILAN JIKA SUDAH DINILAI (HIJAU) --}}
                                <div class="mb-2">
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success rounded-pill">REVIEWED</span>
                                </div>
                                {{-- TOMBOL LIHAT HASIL --}}
                                <button wire:click="evaluate({{ $task->id }})"
                                    class="btn btn-light btn-sm w-100 rounded-pill border-secondary">
                                    <i class="ph-eye me-1"></i> Lihat Hasil
                                </button>
                            @else
                                {{-- TAMPILAN JIKA BELUM (KUNING) --}}
                                <div class="mb-2">
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning rounded-pill">ASSIGNED</span>
                                </div>
                                <button wire:click="evaluate({{ $task->id }})"
                                    class="btn btn-primary btn-sm w-100 rounded-pill">
                                    <i class="ph-pencil-line me-1"></i> Mulai Menilai
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-white opacity-50">
                <i class="ph-file-search ph-4x mb-3"></i>
                <h5>Belum ada tugas review untuk Anda.</h5>
            </div>
        @endforelse
    </div>

    {{-- MODAL FORM PENILAIAN --}}
    @include('features.operator.reviewer.modals.evaluation-form')
</div>



