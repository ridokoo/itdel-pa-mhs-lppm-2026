<div class="card border-0 shadow-none bg-transparent">
    {{-- 1. TAB NAVIGATION --}}
    <div class="nav-tabs-wrapper mb-3">
        <ul class="nav nav-tabs nav-tabs-underline nav-justified border-bottom-0">
            <li class="nav-item">
                <a href="#" class="nav-link active fw-bold text-primary">
                    <i class="ph-house me-2"></i> Usulan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dosen.laporan-kemajuan.index', ['categoryId' => $categoryId]) }}" class="nav-link text-muted text-nowrap">
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

    {{-- 2. JUDUL HALAMAN --}}
    <div class="mb-3">
        <h3 class="fw-bold mb-0">Daftar {{ $title }}</h3>
    </div>

    {{-- 3. TOOLBAR --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        {{-- Kiri: Tombol Buat + Info --}}
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if (!$isPeriodOpen)
                <button class="btn btn-secondary shadow-none" disabled title="Pendaftaran ditutup">
                    <i class="ph-lock me-2"></i> Buat Usulan Baru
                </button>
                <span class="badge bg-danger bg-opacity-10 text-danger py-2 px-3 rounded-pill">
                    <i class="ph-clock-afternoon me-1"></i> {{ $activePeriodInfo }}
                </span>
            @else
                <a href="{{ route('dosen.usulan.form', ['categoryId' => $categoryId]) }}"
                    class="btn btn-primary rounded-pill">
                    <i class="ph-plus-circle me-2"></i> Buat Usulan Baru
                </a>
            @endif

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#modal_eligibilitas">
                <i class="ph-info me-2"></i> Info Eligibilitas
            </button>
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
            @if ($filterYear > date('Y'))
                <small class="text-warning mt-1"><em>Menampilkan rencana usulan masa depan</em></small>
            @endif
        </div>
    </div>

    {{-- 4. TABEL DATA --}}
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase">
                        <th class="text-center py-3" style="width:50px; white-space:nowrap">No.</th>
<th class="py-3" style="white-space:nowrap">Ketua</th>
                        <th class="py-3" style="min-width:200px; white-space:nowrap">Judul</th>
<th class="py-3" style="white-space:nowrap">Skema</th>
<th class="py-3" style="white-space:nowrap">Bidang Fokus</th>
                        <th class="py-3 text-center" style="width:80px; white-space:nowrap">Peran</th>
                        <th class="py-3 text-center" style="width:100px; white-space:nowrap">Status</th>
                        <th class="py-3 text-center" style="width:115px; white-space:nowrap">Komentar LPPM</th>
                        <th class="py-3 text-center" style="width:125px; white-space:nowrap">Komentar Reviewer</th>
                        <th class="py-3 text-center" style="width:95px; white-space:nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myProposals as $p)
                        <tr>
                            {{-- No --}}
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>

                            {{-- Ketua --}}
<td>
    <div class="fw-bold small">
        {{ strtoupper($p->leader?->name) }}
    </div>
    <div class="text-muted" style="font-size:11px">
        {{ $p->leader?->nidn ?? $p->leader?->cis_id }}
    </div>
</td>

                            {{-- Judul --}}
                            <td>
                                <div class="fw-semibold text-primary lh-sm">{{ $p->title }}</div>
                            </td>

                            {{-- Skema --}}
                            <td>
    <div class="small">{{ $p->schemeType?->name ?? '-' }}</div>
</td>

                            {{-- Bidang Fokus --}}
                           <td>
    <div class="small">{{ $p->scope?->name ?? '-' }}</div>
</td>

                            {{-- Peran --}}
                            <td class="text-center">
                                @if ($p->leader_id === $localUser->id)
                                    <span class="badge bg-primary text-white px-2 py-1" style="min-width:65px">Ketua</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1" style="min-width:65px">Anggota</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @php
                                    $statusName = strtolower($p->status?->name ?? '');
                                    $color = 'bg-secondary';
                                    if (str_contains($statusName, 'draft'))      $color = 'bg-secondary';
                                    if (str_contains($statusName, 'aju'))        $color = 'bg-info';
                                    if (str_contains($statusName, 'review'))     $color = 'bg-info';
                                    if (str_contains($statusName, 'setuju') || str_contains($statusName, 'approved')) $color = 'bg-success';
                                    if (str_contains($statusName, 'revision') || str_contains($statusName, 'revisi')) $color = 'bg-warning';
                                    if (str_contains($statusName, 'tolak') || str_contains($statusName, 'tidak') || str_contains($statusName, 'rejected')) $color = 'bg-danger';
                                @endphp
                                <span class="badge {{ $color }} px-2 py-1" style="white-space:nowrap">
                                    {{ strtoupper($p->status?->name ?? 'N/A') }}
                                </span>
                            </td>

                            {{-- Komentar LPPM --}}
                            <td class="text-center">
                                @if ($p->decision_note)
                                    <button type="button" wire:click="showComment('{{ $p->id }}', 'lppm')"
                                        class="btn btn-indigo btn-sm px-3 rounded-pill shadow-none"
                                        title="Ada komentar dari LPPM">
                                        <i class="ph-bell-fill me-1"></i> Lihat
                                    </button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Komentar Reviewer --}}
                            <td class="text-center">
                                @php
                                    $hasReviewerNotes = false;
                                    if ($p->reviewers && $p->reviewers instanceof \Illuminate\Database\Eloquent\Collection) {
                                        $hasReviewerNotes = $p->reviewers->whereNotNull('notes')->count() > 0;
                                    }
                                @endphp
                                @if ($hasReviewerNotes)
                                    <button type="button" wire:click="showComment('{{ $p->id }}', 'reviewer')"
                                        class="btn btn-primary btn-sm px-3 rounded-pill shadow-none"
                                        title="Ada catatan dari Reviewer">
                                        <i class="ph-bell-fill me-1"></i> Lihat
                                    </button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                @php
                                    $statusName  = strtolower($p->status?->name ?? '');
                                    $isLeader    = $p->leader_id === $localUser->id;
                                    $isEditable  = $isLeader && (
                                        str_contains($statusName, 'draft') ||
                                        str_contains($statusName, 'revisi') ||
                                        str_contains($statusName, 'revision')
                                    );
                                    $blockedStatuses = ['diajukan','aju','review','approved','setuju','revisi','revision'];
                                    $isDeletable = $isLeader && !collect($blockedStatuses)->contains(fn($s) => str_contains($statusName, $s));

                                    $editTitle   = match(true) {
                                        !$isLeader   => 'Hanya ketua yang dapat mengedit usulan',
                                        !$isEditable => 'Tidak bisa diedit — status: ' . ($p->status?->name ?? ''),
                                        default      => 'Edit Usulan',
                                    };
                                    $deleteTitle = match(true) {
                                        !$isLeader    => 'Hanya ketua yang dapat menghapus usulan',
                                        !$isDeletable => 'Tidak bisa dihapus — status: ' . ($p->status?->name ?? ''),
                                        default       => 'Hapus Usulan',
                                    };
                                @endphp

                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Lihat --}}
                                    <button type="button" wire:click="viewDetail('{{ $p->id }}')"
                                        class="btn btn-outline-info btn-sm" title="Lihat Detail"
                                        style="width:34px;height:34px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                                            <path d="M247.31,124.76c-.35-.79-8.82-19.58-27.65-38.41C194.57,61.26,162.88,48,128,48S61.43,61.26,36.34,86.35C17.51,105.18,9,124,8.69,124.76a8,8,0,0,0,0,6.48c.35.79,8.82,19.57,27.65,38.41C61.43,194.74,93.12,208,128,208s66.57-13.26,91.66-38.35c18.83-18.84,27.3-37.62,27.65-38.41A8,8,0,0,0,247.31,124.76ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.47,133.47,0,0,1,25,128,133.33,133.33,0,0,1,48.07,97.25C70.33,75.19,97.22,64,128,64s57.67,11.19,79.93,33.25A133.52,133.52,0,0,1,231.05,128C223.84,141.46,192.43,192,128,192Zm0-112a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"/>
                                        </svg>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button"
                                        @if ($isEditable) wire:click="edit('{{ $p->id }}')" @endif
                                        class="btn btn-sm {{ $isEditable ? 'btn-outline-primary' : 'btn-outline-secondary' }}"
                                        title="{{ $editTitle }}"
                                        style="width:34px;height:34px;padding:0;display:inline-flex;align-items:center;justify-content:center;"
                                        {{ !$isEditable ? 'disabled' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                                            <path d="M227.31,73.37,182.63,28.68a16,16,0,0,0-22.63,0L36.69,152A15.86,15.86,0,0,0,32,163.31V208a16,16,0,0,0,16,16H92.69A15.86,15.86,0,0,0,104,219.31L227.31,96a16,16,0,0,0,0-22.63ZM92.69,208H48V163.31l88-88L180.69,120ZM192,108.68,147.31,64l24-24L216,84.68Z"/>
                                        </svg>
                                    </button>

                                    {{-- Hapus --}}
                                    <button type="button"
                                        @if ($isDeletable) wire:click="deleteConfirm('{{ $p->id }}')" @endif
                                        class="btn btn-sm {{ $isDeletable ? 'btn-outline-danger' : 'btn-outline-secondary' }}"
                                        title="{{ $deleteTitle }}"
                                        style="width:34px;height:34px;padding:0;display:inline-flex;align-items:center;justify-content:center;"
                                        {{ !$isDeletable ? 'disabled' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
                                            <path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <div class="mb-2">
                                    <i class="ph-info" style="font-size:3rem; opacity:.2; display:block; margin-bottom:8px"></i>
                                </div>
                                Data tidak tersedia!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-3 py-2 border-top bg-light">
            <span class="text-muted small">Menampilkan {{ $myProposals->count() }} usulan</span>
        </div>
    </div>

    {{-- MODAL INFO ELIGIBILITAS --}}
    <div wire:ignore.self class="modal fade" id="modal_eligibilitas" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white border-0">
                    <h6 class="modal-title"><i class="ph-info me-2"></i> Status Eligibilitas Pengusul</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded h-100">
                                <h6 class="fw-bold mb-2 small text-uppercase text-muted">Profil Akademik Anda:</h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Jabatan Fungsional:</small>
                                    <span class="fw-bold">{{ $userProfile?->functionalPosition?->name ?? 'Belum Diatur' }}</span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Program Studi:</small>
                                    <span class="fw-bold">{{ $userProfile?->studyProgram?->name ?? 'Belum Diatur' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded h-100">
                                <h6 class="fw-bold mb-2 small text-uppercase text-muted">Pendidikan & Identitas:</h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block">Pendidikan Terakhir:</small>
                                    <span class="fw-bold">
                                        {{ str_contains(strtoupper(auth()->user()->name ?? ''), 'DR.') ? 'S3 (Doktor)' : 'S2 (Magister)' }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block">NIDN:</small>
                                    <span class="fw-bold text-primary">{{ $userProfile->nidn ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-primary border-opacity-20 bg-primary bg-opacity-5">
                        <div class="card-body">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="ph-check-circle me-2"></i>
                                Berdasarkan eligibilitas di atas, Anda dapat mengusulkan skema berikut:
                            </h6>
                            @if (count($eligibleSchemes) > 0)
                                <div class="row">
                                    @foreach ($eligibleSchemes as $index => $schemeName)
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary rounded-pill me-2">{{ $index + 1 }}</span>
                                                <span class="fw-semibold">{{ $schemeName }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-danger border-0 mb-0">
                                    <i class="ph-x-circle me-2"></i>
                                    Maaf, data profil Anda belum memenuhi kriteria untuk skema penelitian yang tersedia saat ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('dosen.profil') }}" class="btn btn-primary rounded-pill">
    <i class="ph-user-edit me-2"></i> Perbarui Profil
</a>


                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INCLUDE --}}
    @include('features.dosen.usulan.modals.delete-usulan')
    @include('features.dosen.usulan.modals.view-comment')
</div>
