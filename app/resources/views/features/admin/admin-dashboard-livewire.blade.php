@php
    $apiUser = request()->auth ?? auth()->user();
    $role = strtolower($apiUser?->role ?? 'guest');
    $isDosen = in_array($role, ['dosen', 'reviewer']);

    // Active role dari session (default ke $role jika belum di-set)
    $activeRole = in_array($role, ['dosen', 'reviewer']) ? strtolower(session('active_role', $role)) : $role;

    $allCategories = \App\Models\Category::orderBy('name')->get();
    $kategoriPenelitian = $allCategories->first(fn($c) => str_contains(strtolower($c->name), 'penelitian'));
    $kategoriPengabdian = $allCategories->first(fn($c) => str_contains(strtolower($c->name), 'pengabdian'));
    $kategoriKonsorsium = $allCategories->first(fn($c) => str_contains(strtolower($c->name), 'konsorsium'));
    $kategoriPrototipe = $allCategories->first(fn($c) => str_contains(strtolower($c->name), 'prototipe'));
    $statPenelitian = 0;
    $statPengabdian = 0;
    $statKonsorsium = 0;
    $statPrototipe = 0;

    // Load profil lokal
    $profile = null;
    $localUser = null;
    if ($isDosen && $apiUser?->email) {
        $localUser = \App\Models\User::where('email', $apiUser->email)
            ->with(['profile.studyProgram', 'profile.functionalPosition'])
            ->first();
        $profile = $localUser?->profile;
    }

    $educationLevel = 'S2'; // Default
    if ($localUser) {
        // Cek jika nama mengandung gelar Doktor
        if (preg_match('/(DR\.|PH\.D)/i', strtoupper($localUser->name))) {
            $educationLevel = 'S3';
        }
    }

    // Data reviewer jika activeRole == reviewer
    $reviewerUser = null;
    $totalTugas = 0;
    $selesai = 0;
    $menunggu = 0;
    $recentReviews = collect();

    if ($activeRole === 'reviewer' && $localUser) {
        $reviewerUser = $localUser;

        // 1. Total semua tugas yang pernah diberikan
        $totalTugas = \App\Models\ProposalReviewer::where('reviewer_id', $reviewerUser->id)->count();

        // 2. TUGAS SELESAI: Gunakan completed_at sebagai kunci utama
        $selesai = \App\Models\ProposalReviewer::where('reviewer_id', $reviewerUser->id)
            ->whereNotNull('completed_at')
            ->count();

        // 3. TUGAS MENUNGGU: Tugas yang completed_at-nya masih kosong
        $menunggu = \App\Models\ProposalReviewer::where('reviewer_id', $reviewerUser->id)
            ->whereNull('completed_at')
            ->count();

        // 4. Riwayat Review Terbaru (Mengambil 4 terakhir)
        $recentReviews = \App\Models\ProposalReviewer::with('proposal')
            ->where('reviewer_id', $reviewerUser->id)
            ->latest('assigned_at')
            ->take(4)
            ->get();
    }
@endphp

<div>
    <style>
        /* ================================================================
           DASHBOARD STYLES
        ================================================================ */

        /* --- Reset area konten --- */
        .content-inner,
        .content,
        .page-header {
            background-color: #f5f7fa !important;
        }

        /* --- Hero Section --- */
        .bima-hero {
            background: linear-gradient(135deg, #1e3799 0%, #0c2461 100%);
            border-radius: 16px;
            color: white;
            padding: 36px 40px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            min-height: 220px;
        }

        .hero-date-box {
            background: rgba(255, 255, 255, 0.18);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            margin-bottom: 14px;
            backdrop-filter: blur(4px);
        }

        .bima-hero h2 {
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .bima-hero h4 {
            font-size: 1rem;
        }

        .bima-hero .period-info {
            font-size: 0.82rem;
            opacity: 0.92;
            max-width: 65%;
        }

        .hero-character {
            position: absolute;
            right: 30px;
            bottom: 0;
            height: 230px;
            z-index: 1;
        }

        /* --- Stat Cards --- */
        .card-stat-bima {
            border: none;
            border-radius: 14px;
            color: white;
            padding: 22px 20px;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            text-decoration: none !important;
        }

        .card-stat-bima:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15) !important;
        }

        .stat-number {
            font-size: 2.6rem;
            font-weight: 800;
            margin-right: 16px;
            line-height: 1;
            flex-shrink: 0;
        }

        .stat-text .title {
            font-weight: 700;
            font-size: 1rem;
            display: block;
            margin-bottom: 2px;
        }

        .stat-text .sub {
            font-size: 0.72rem;
            opacity: 0.82;
        }

        .stat-icon-bg {
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.13);
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .bg-red {
            background: linear-gradient(135deg, #ff5252, #d32f2f);
        }

        .bg-cyan {
            background: linear-gradient(135deg, #00bcd4, #0097a7);
        }

        .bg-orange {
            background: linear-gradient(135deg, #ff9800, #e65100);
        }

        .bg-green {
            background: linear-gradient(135deg, #4caf50, #2e7d32);
        }

        .bg-blue {
            background: linear-gradient(135deg, #1e88e5, #1565c0);
        }

        /* --- Profile & Side Cards --- */
        .bima-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #f0f2f5;
        }

        .bima-card-header {
            background: #0c2461;
            color: white;
            padding: 13px 18px;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-avatar {
            width: 72px;
            height: 72px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #adb5bd;
            flex-shrink: 0;
        }

        .tag-aktif {
            background: #e6f9f4;
            color: #0070C0;
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.3px;
        }

        .tag-reviewer {
            background: #e8eaf6;
            color: #3f51b5;
            font-size: 0.65rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.3px;
        }

        .profile-info h6 {
            color: #0c2461;
            font-weight: 700;
            margin-bottom: 3px;
            font-size: 0.95rem;
        }

        .profile-info p {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .profile-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            border-top: 1px solid #f0f2f5;
            margin-top: 15px;
        }

        .stat-item {
            padding: 11px 6px;
            text-align: center;
            border-right: 1px solid #f0f2f5;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-item .label {
            display: block;
            font-size: 0.63rem;
            color: #adb5bd;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .stat-item .value {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #0c2461;
        }

        /* Riwayat list */
        .list-usulan {
            list-style: none;
            padding: 0;
            margin-top: 8px;
        }

        .list-usulan li {
            position: relative;
            padding-left: 16px;
            margin-bottom: 10px;
            font-size: 0.79rem;
            color: #6c757d;
            line-height: 1.4;
        }

        .list-usulan li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            width: 7px;
            height: 7px;
            background: #dee2e6;
            border-radius: 50%;
        }

        .more-link {
            font-size: 0.7rem;
            color: #0c2461;
            font-style: italic;
            font-weight: 600;
            text-decoration: none;
        }

        .more-link:hover {
            color: #0070C0;
        }

        /* Reviewer banner CTA */
        .reviewer-cta {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 14px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none !important;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .reviewer-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
        }

        .reviewer-cta-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
        }
    </style>

    {{-- ================================================================ --}}
    {{-- DOSEN / STAFF / REVIEWER VIEW                                     --}}
    {{-- ================================================================ --}}
    @if ($isDosen)
        @php
            if (in_array($activeRole, ['dosen', 'staff']) && $localUser) {
                $proposalIds = \App\Models\ProposalMember::where('user_id', $localUser->id)
                    ->pluck('proposal_id')
                    ->merge(\App\Models\Proposal::where('leader_id', $localUser->id)->pluck('id'))
                    ->unique();

                $statPenelitian = $kategoriPenelitian
                    ? \App\Models\Proposal::whereIn('id', $proposalIds)
                        ->where('category_id', $kategoriPenelitian->id)
                        ->count()
                    : 0;

                $statPengabdian = $kategoriPengabdian
                    ? \App\Models\Proposal::whereIn('id', $proposalIds)
                        ->where('category_id', $kategoriPengabdian->id)
                        ->count()
                    : 0;

                $statKonsorsium = \App\Models\ProposalMember::where('user_id', auth()->id())
                    ->where('member_status_id', 1)
                    ->count();

                $statPrototipe = $kategoriPrototipe
                    ? \App\Models\Proposal::whereIn('id', $proposalIds)
                        ->where('category_id', $kategoriPrototipe->id)
                        ->count()
                    : 0;
            }

            // Data stat cards REVIEWER
            $reviewerUser = null;
            $totalTugas = 0;
            $selesai = 0;
            $menunggu = 0;
            $recentReviews = collect();

            if ($activeRole === 'reviewer' && $localUser) {
                $reviewerUser = $localUser;
                $totalTugas = \App\Models\ProposalReviewer::where('reviewer_id', $reviewerUser->id)->count();
                $selesai = \App\Models\ProposalReviewer::where('reviewer_id', $reviewerUser->id)
                    ->whereNotNull('completed_at')
                    ->count();
                $menunggu = $totalTugas - $selesai;
                $recentReviews = \App\Models\ProposalReviewer::with('proposal')
                    ->where('reviewer_id', $reviewerUser->id)
                    ->latest('assigned_at')
                    ->take(4)
                    ->get();
            }

            // Data riwayat usulan
            $riwayatPenelitian = collect();
            $riwayatPengabdian = collect();

            if (in_array($activeRole, ['dosen', 'staff']) && $localUser) {
                $riwayatPenelitian = $kategoriPenelitian
                    ? \App\Models\Proposal::where(function ($q) use ($localUser) {
                        $q->where('leader_id', $localUser->id)->orWhereHas(
                            'proposalMembers',
                            fn($m) => $m->where('user_id', $localUser->id),
                        );
                    })
                        ->where('category_id', $kategoriPenelitian->id)
                        ->latest()
                        ->take(3)
                        ->get()
                    : collect();

                $riwayatPengabdian = $kategoriPengabdian
                    ? \App\Models\Proposal::where(function ($q) use ($localUser) {
                        $q->where('leader_id', $localUser->id)->orWhereHas(
                            'proposalMembers',
                            fn($m) => $m->where('user_id', $localUser->id),
                        );
                    })
                        ->where('category_id', $kategoriPengabdian->id)
                        ->latest()
                        ->take(3)
                        ->get()
                    : collect();
            }
        @endphp

        <div class="row g-4">

            {{-- ── KIRI ──────────────────────────────────────────────── --}}
            <div class="col-lg-8">

                {{-- Hero --}}
                <div class="bima-hero shadow">
                    <div class="hero-date-box">
                        <i class="ph-calendar-blank me-2"></i> {{ now()->isoFormat('D MMMM Y') }}
                    </div>
                    <h2>Selamat Datang,<br>{{ $apiUser->name }}!</h2>
                    <h4 class="fw-light opacity-75 mb-3">Have a nice {{ now()->isoFormat('dddd') }}!</h4>

                    <div class="period-info">
                        @if ($activePeriod ?? null)
                            <div class="d-flex align-items-start gap-2">
                                <i class="ph-info fs-5 text-warning mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-warning text-uppercase"
                                        style="font-size:0.68rem;letter-spacing:.5px;">
                                        Periode Saat Ini:
                                    </strong>
                                    <div class="fw-bold" style="font-size:.95rem;">
                                        {{ $activePeriod->name }} - {{ $activePeriod->year }}
                                    </div>
                                    <div class="opacity-75" style="font-size:.78rem;">
                                        {{ \Carbon\Carbon::parse($activePeriod->start_date)->format('d M Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($activePeriod->end_date)->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph-clock-afternoon fs-5 opacity-50"></i>
                                <span class="fst-italic opacity-75">Belum ada periode pendaftaran yang aktif.</span>
                            </div>
                        @endif
                    </div>

                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/businessman-working-on-laptop-illustration-download-in-svg-png-gif-formats--man-at-desk-freelancer-work-from-home-pack-people-illustrations-5349079.png"
                        class="hero-character d-none d-xl-block" alt="">
                </div>

                {{-- ── STAT CARDS: DOSEN / STAFF ── --}}
                @if (in_array($activeRole, ['dosen', 'staff']))
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ $kategoriPenelitian ? route('dosen.usulan.index', ['categoryId' => $kategoriPenelitian->id]) : '#' }}"
                                class="card-stat-bima bg-red shadow-sm d-flex text-decoration-none">
                                <div class="stat-number">{{ $statPenelitian }}</div>
                                <div class="stat-text">
                                    <span class="title">Penelitian</span>
                                    <span class="sub">Total usulan Anda</span>
                                </div>
                                <div class="stat-icon-bg"><i class="ph-magnifying-glass"></i></div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ $kategoriPengabdian ? route('dosen.usulan.index', ['categoryId' => $kategoriPengabdian->id]) : '#' }}"
                                class="card-stat-bima bg-cyan shadow-sm d-flex text-decoration-none">
                                <div class="stat-number">{{ $statPengabdian }}</div>
                                <div class="stat-text">
                                    <span class="title">Pengabdian</span>
                                    <span class="sub">Total usulan Anda</span>
                                </div>
                                <div class="stat-icon-bg"><i class="ph-file-text"></i></div>
                            </a>
                        </div>
                        <a href="{{ route('dosen.undangan') }}"
                            class="card-stat-bima bg-orange shadow-sm text-decoration-none">
                            <div class="stat-number">{{ $statKonsorsium }}</div>
                            <div class="stat-text">
                                <span class="title">Invitation</span>
                                <span class="sub">Menunggu konfirmasi</span>
                            </div>
                            <div class="stat-icon-bg"><i class="ph-envelope-simple-open"></i></div>
                        </a>
                        {{-- <div class="col-6">
                            <div class="card-stat-bima bg-green shadow-sm">
                                <div class="stat-number">{{ $statPrototipe }}</div>
                                <div class="stat-text">
                                    <span class="title">Prototipe</span>
                                    <span class="sub">Total usulan Anda</span>
                                </div>
                                <div class="stat-icon-bg"><i class="ph-cube"></i></div>
                            </div>
                        </div> --}}
                    </div>
                @endif

                {{-- ── STAT CARDS: REVIEWER ── --}}
                @if ($activeRole === 'reviewer')
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <a href="{{ route('reviewer.dashboard') }}"
                                class="card-stat-bima bg-blue shadow-sm d-flex text-decoration-none">
                                <div class="stat-number">{{ $totalTugas }}</div>
                                <div class="stat-text">
                                    <span class="title">Total Tugas</span>
                                    <span class="sub">Proposal ditugaskan</span>
                                </div>
                                <div class="stat-icon-bg"><i class="ph-clipboard-text"></i></div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reviewer.dashboard') }}"
                                class="card-stat-bima bg-green shadow-sm d-flex text-decoration-none">
                                <div class="stat-number">{{ $selesai }}</div>
                                <div class="stat-text">
                                    <span class="title">Selesai</span>
                                    <span class="sub">Sudah direview</span>
                                </div>
                                <div class="stat-icon-bg"><i class="ph-check-circle"></i></div>
                            </a>
                        </div>
                    </div>

                    {{-- CTA Banner Reviewer --}}
                    <a href="{{ route('reviewer.dashboard') }}" class="reviewer-cta shadow-sm"
                        style="background-color: #b91c1c; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 16px; border-radius: 12px;">
                        <div class="reviewer-cta-icon">
                            <i class="ph-signature" style="color: white; font-size: 28px;"></i>
                        </div>
                        <div class="flex-fill">
                            <div class="fw-bold" style="font-size:.95rem; color: white;">Mulai Review Proposal</div>
                            <div class="small mt-1" style="color: white; opacity: 0.9;">
                                @if ($menunggu > 0)
                                    Ada <strong style="color: #facc15;">{{ $menunggu }} proposal</strong> menunggu
                                    review Anda.
                                @else
                                    Semua tugas review sudah selesai. Kerja bagus! 🎉
                                @endif
                            </div>
                        </div>
                        <i class="ph-arrow-right" style="color: white; font-size:22px; opacity:0.7;"></i>
                    </a>
                @endif

            </div>{{-- /col-lg-8 --}}

            {{-- ── KANAN: PROFIL SAYA & REVIEWER USULAN ────────────────── --}}
            <div class="col-lg-4">

                {{-- Card Profil Saya --}}
                <div class="bima-card shadow-sm">
                    <div class="bima-card-header">
                        <span>Profil Saya</span>
                        <a href="{{ route('dosen.profil') }}" class="text-white opacity-60" title="Edit Profil">
                            <i class="ph-pencil-simple"></i>
                        </a>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="profile-avatar">
                                <i class="ph-user"></i>
                            </div>
                            <div class="profile-info">
                                <h6>{{ $apiUser->name }}</h6>
                                <p class="mb-1">
                                    {{ $profile?->studyProgram?->name ?? 'Program Studi' }}<br>
                                    Institut Teknologi Del
                                </p>
                                @if ($activeRole === 'reviewer')
                                    <span class="tag-reviewer"><i class="ph-star me-1"></i>Reviewer Aktif</span>
                                @else
                                    <span class="tag-aktif"><i class="ph-check me-1"></i>Aktif Mengajar</span>
                                @endif
                            </div>
                        </div>

                        <div class="profile-stats-grid">
                            <div class="stat-item">
                                <span class="label">Jenjang Pendidikan</span>
                                <span class="value">{{ $educationLevel }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Jabatan Akademik</span>
                                <span class="value text-primary" style="font-size:0.75rem;">
                                    {{ $profile?->functionalPosition?->name ?? 'Belum Diatur' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (!$profile || !$profile->study_program_id || !$profile->functional_position_id)
                        <div class="px-3 pb-3">
                            <div class="alert alert-warning py-2 px-3 mb-0 d-flex align-items-center gap-2"
                                style="font-size:0.75rem;border-radius:8px;">
                                <i class="ph-warning-circle flex-shrink-0"></i>
                                <span>Profil belum lengkap.
                                    <a href="{{ route('dosen.profil') }}" class="fw-bold text-warning">
                                        Lengkapi sekarang →
                                    </a>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Card Reviewer Usulan (Riwayat Review) — HANYA reviewer --}}
                @if ($activeRole === 'reviewer')
                    <div class="bima-card shadow-sm">
                        <div class="bima-card-header" style="background:#2c3e50;">
                            <span>Reviewer Usulan</span>
                        </div>
                        <div class="card-body p-3">
                            @forelse ($recentReviews as $rev)
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <div class="flex-shrink-0 mt-1">
                                        @if ($rev->completed_at)
                                            <i class="ph-check-circle text-success" style="font-size: 1.2rem;"></i>
                                        @else
                                            <i class="ph-hourglass text-warning" style="font-size: 1.2rem;"></i>
                                        @endif
                                    </div>
                                    <div class="flex-fill overflow-hidden">
                                        <div class="small fw-semibold text-truncate"
                                            title="{{ $rev->proposal?->title }}">
                                            {{ $rev->proposal?->title ?? '-' }}
                                        </div>
                                        <div class="text-muted" style="font-size:.7rem;">
                                            @if ($rev->completed_at)
                                                <span class="text-success fw-bold text-uppercase">Selesai
                                                    Direview</span>
                                                <br>
                                                <small class="opacity-50">Tgl:
                                                    {{ \Carbon\Carbon::parse($rev->completed_at)->format('d/m/y') }}</small>
                                            @else
                                                <span class="text-warning fw-bold text-uppercase">Menunggu
                                                    Review</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3 small fst-italic">
                                    <i class="ph-clipboard-text d-block mb-2"
                                        style="font-size:1.8rem;opacity:.3;"></i>
                                    Belum ada riwayat review.
                                </div>
                            @endforelse
                            <div class="text-end mt-1">
                                <a href="{{ route('reviewer.dashboard') }}" class="more-link">Lihat semua →</a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── RIWAYAT USULAN (hanya untuk dosen/staff) ── --}}
                @if (in_array($activeRole, ['dosen', 'staff']))
                    <div class="bima-card shadow-sm">
                        <div class="bima-card-header" style="background:#2c3e50;">
                            <span>Riwayat Usulan</span>
                        </div>
                        <div class="card-body p-3">
                            {{-- Penelitian --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold" style="font-size:.82rem;">Penelitian</span>
                                    <a href="{{ $kategoriPenelitian ? route('dosen.usulan.index', ['categoryId' => $kategoriPenelitian->id]) : '#' }}"
                                        class="small text-primary text-decoration-none">lihat semua →</a>
                                </div>
                                <ul class="list-usulan">
                                    @forelse ($riwayatPenelitian as $p)
                                        <li>{{ $p->title }}</li>
                                    @empty
                                        <li class="fst-italic" style="color:#adb5bd;">Tidak ada usulan penelitian.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                            {{-- Pengabdian --}}
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold" style="font-size:.82rem;">Pengabdian</span>
                                    <a href="{{ $kategoriPengabdian ? route('dosen.usulan.index', ['categoryId' => $kategoriPengabdian->id]) : '#' }}"
                                        class="small text-primary text-decoration-none">lihat semua →</a>
                                </div>
                                <ul class="list-usulan">
                                    @forelse ($riwayatPengabdian as $p)
                                        <li>{{ $p->title }}</li>
                                    @empty
                                        <li class="fst-italic" style="color:#adb5bd;">Tidak ada usulan pengabdian.
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}
    @elseif (str_contains($role, 'admin'))
        {{-- ADMIN VIEW (tetap sama) --}}
        <div class="d-flex align-items-center mb-4">
            <div class="icon-box-modern accent-green me-3" style="margin-bottom:0;">
                <i class="ph-bold ph-chart-line-up"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-0">Dashboard Pusat Kontrol</h3>
                <p class="text-muted mb-0">Halo {{ $apiUser?->name ?? 'Pengguna' }}, kelola operasional LPPM hari ini.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-purple"><i class="ph-bold ph-house"></i></div>
                        <span class="stat-count" style="color:#6f42c1;">Total Data: {{ $total_beranda ?? 0 }}</span>
                        <h2 class="stat-title-text">Kelola Beranda</h2>
                        <p class="stat-desc-text">Atur informasi utama, sambutan, serta Visi & Misi lembaga pada
                            halaman publik.</p>
                        <a href="{{ route('admin.beranda') }}" class="btn-modern btn-purple">Konfigurasi <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-orange"><i class="ph-bold ph-newspaper"></i></div>
                        <span class="stat-count" style="color:#fd7e14;">Total Berita: {{ $total_berita ?? 0 }}</span>
                        <h2 class="stat-title-text">Berita & Kegiatan</h2>
                        <p class="stat-desc-text">Update artikel hasil penelitian dan kegiatan pengabdian masyarakat
                            terbaru.</p>
                        <a href="{{ route('admin.berita') }}" class="btn-modern btn-orange">Tulis Berita <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-green"><i class="ph-bold ph-megaphone"></i></div>
                        <span class="stat-count" style="color:#0070C0;">Aktif: {{ $total_pengumuman ?? 0 }}</span>
                        <h2 class="stat-title-text">Pengumuman</h2>
                        <p class="stat-desc-text">Sebarkan informasi resmi, jadwal penting, dan edaran kampus melalui
                            portal.</p>
                        <a href="{{ route('admin.pengumuman') }}" class="btn-modern btn-green">Kelola Info <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-cyan"><i class="ph-bold ph-image"></i></div>
                        <span class="stat-count" style="color:#0dcaf0;">Album: {{ $total_galeri ?? 0 }}</span>
                        <h2 class="stat-title-text">Galeri Foto</h2>
                        <p class="stat-desc-text">Manajemen dokumentasi visual kegiatan LPPM dalam bentuk album foto.
                        </p>
                        <a href="{{ route('admin.gallery') }}" class="btn-modern btn-cyan">Atur Galeri <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-pink"><i class="ph-bold ph-folders"></i></div>
                        <span class="stat-count" style="color:#d63384;">Kategori: {{ $total_kategori ?? 0 }}</span>
                        <h2 class="stat-title-text">Kategori Dokumen</h2>
                        <p class="stat-desc-text">Kelola pengelompokan file seperti panduan, template, atau laporan
                            akhir.</p>
                        <a href="{{ route('admin.kategori-dokumen') }}" class="btn-modern btn-pink">Atur Kategori <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-yellow"><i class="ph-bold ph-address-book"></i></div>
                        <span class="stat-count" style="color:#fbc02d;">Data: {{ $total_kontak ?? 0 }}</span>
                        <h2 class="stat-title-text">Informasi Kontak</h2>
                        <p class="stat-desc-text">Atur nomor telepon, email, dan akun media sosial resmi LPPM IT Del.
                        </p>
                        <a href="{{ route('admin.kontak') }}" class="btn-modern btn-yellow">Atur Kontak <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @elseif (str_contains($role, 'ketua'))
        {{-- KETUA VIEW (tetap sama) --}}
        @php
            $totalDiajukan = \App\Models\Proposal::whereHas(
                'status',
                fn($q) => $q->where('name', 'Submitted'),
            )->count();

            $totalReview = \App\Models\Proposal::whereHas('status', fn($q) => $q->where('name', 'Review'))->count();

            $totalDisetujui = \App\Models\Proposal::whereHas(
                'status',
                fn($q) => $q->whereIn('name', ['Approved', 'Diterima', 'Diterima Dengan Revisi']),
            )->count();

            $totalDitolak = \App\Models\Proposal::whereHas(
                'status',
                fn($q) => $q->whereIn('name', ['Rejected', 'Ditolak']),
            )->count();

            $menungguPersetujuan = \App\Models\Proposal::with(['leader', 'status'])
                ->whereHas('status', fn($q) => $q->whereIn('name', ['Review', 'Submitted']))
                ->latest()
                ->take(5)
                ->get();
        @endphp

        <div class="d-flex align-items-center mb-4">
            <div class="icon-box-modern accent-indigo me-3" style="margin-bottom:0;">
                <i class="ph-bold ph-seal-check"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-0">Panel Ketua LPPM</h3>
                <p class="text-muted mb-0">Halo <strong>{{ $apiUser?->name ?? 'Ketua LPPM' }}</strong>, tinjau dan
                    berikan keputusan akhir untuk usulan yang masuk.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('ketua.dashboard') }}"
                            class="card-stat-bima bg-orange shadow-sm d-flex text-decoration-none">
                            <div class="stat-number">{{ $totalDiajukan }}</div>
                            <div class="stat-text"><span class="title">Diajukan</span><span class="sub">Menunggu
                                    proses</span></div>
                            <div class="stat-icon-bg"><i class="ph-paper-plane-tilt"></i></div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('ketua.dashboard') }}"
                            class="card-stat-bima bg-blue shadow-sm d-flex text-decoration-none">
                            <div class="stat-number">{{ $totalReview }}</div>
                            <div class="stat-text"><span class="title">Direview</span><span class="sub">Sedang
                                    ditinjau</span></div>
                            <div class="stat-icon-bg"><i class="ph-clipboard-text"></i></div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('ketua.dashboard') }}"
                            class="card-stat-bima bg-green shadow-sm d-flex text-decoration-none">
                            <div class="stat-number">{{ $totalDisetujui }}</div>
                            <div class="stat-text"><span class="title">Disetujui</span><span
                                    class="sub">Keputusan final</span></div>
                            <div class="stat-icon-bg"><i class="ph-seal-check"></i></div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('ketua.dashboard') }}"
                            class="card-stat-bima bg-red shadow-sm d-flex text-decoration-none">
                            <div class="stat-number">{{ $totalDitolak }}</div>
                            <div class="stat-text"><span class="title">Ditolak</span><span class="sub">Tidak
                                    lolos</span></div>
                            <div class="stat-icon-bg"><i class="ph-x-circle"></i></div>
                        </a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
                    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4"
                        style="background:#0c2461;border:none;">
                        <span class="fw-bold text-white"><i class="ph-clock-countdown me-2 text-warning"></i>Usulan
                            Menunggu Keputusan</span>
                        <a href="{{ route('ketua.dashboard') }}" class="btn btn-sm btn-light rounded-pill px-3"
                            style="font-size:.75rem;">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th class="ps-4">Judul Usulan</th>
                                        <th>Pengusul</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($menungguPersetujuan as $p)
                                        <tr>
                                            <td class="ps-4" style="max-width:280px;">
                                                <div class="fw-semibold text-truncate" title="{{ $p->title }}">
                                                    {{ $p->title }}</div>
                                            </td>
                                            <td class="text-muted small">{{ $p->leader?->name ?? '-' }}</td>
                                            <td class="text-center"><span class="badge rounded-pill px-3 py-1"
                                                    style="background:#fff3e0;color:#e65100;font-size:.72rem;">{{ $p->status?->name ?? '-' }}</span>
                                            </td>
                                            <td class="text-center pe-4"><a href="{{ route('ketua.dashboard') }}"
                                                    class="btn btn-sm btn-primary rounded-pill px-3"
                                                    style="font-size:.75rem;"><i class="ph-eye me-1"></i> Tinjau</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted"><i
                                                    class="ph-check-circle d-block mb-2"
                                                    style="font-size:2rem;color:#4caf50;opacity:.5;"></i>Tidak ada
                                                usulan yang menunggu keputusan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bima-card shadow-sm mb-4">
                    <div class="bima-card-header"><span>Identitas Ketua</span></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="profile-avatar"><i class="ph-user-circle-gear"></i></div>
                            <div class="profile-info">
                                <h6>{{ $apiUser?->name }}</h6>
                                <p class="mb-1">Institut Teknologi Del</p>
                                <span class="badge rounded-pill px-3 py-1"
                                    style="background:#e8eaf6;color:#3f51b5;font-size:.65rem;font-weight:700;"><i
                                        class="ph-seal-check me-1"></i> Ketua LPPM</span>
                            </div>
                        </div>
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="p-2 rounded" style="background:#f8f9fa;">
                                    <div class="fw-bold" style="font-size:1.4rem;color:#0c2461;">
                                        {{ $totalDiajukan + $totalReview }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">Perlu Ditinjau</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded" style="background:#f8f9fa;">
                                    <div class="fw-bold" style="font-size:1.4rem;color:#4caf50;">
                                        {{ $totalDisetujui }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">Sudah Disetujui</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('ketua.dashboard') }}" class="reviewer-cta shadow-sm">
                    <div class="reviewer-cta-icon"><i class="ph-seal-check"></i></div>
                    <div class="flex-fill">
                        <div class="fw-bold text-white" style="font-size:.95rem;">Persetujuan Final Usulan</div>
                        <div class="text-white small mt-1" style="opacity:.75;">
                            @if ($totalDiajukan + $totalReview > 0)
                                Ada
                                <strong class="text-warning">{{ $totalDiajukan + $totalReview }} usulan</strong>
                                menunggu keputusan Anda.
                            @else
                                Semua usulan sudah mendapat keputusan.
                            @endif
                        </div>
                    </div>
                    <i class="ph-arrow-right text-white flex-shrink-0" style="font-size:22px;opacity:.4;"></i>
                </a>
            </div>
        </div>
        
    @elseif (in_array($role, ['mahasiswa', 'staff']))
        {{-- MAHASISWA / STAFF VIEW: dashboard belum tersedia --}}
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Hero info --}}
                <div class="bima-card shadow-sm mb-4">
                    <div class="bima-card-header" style="background: #7f5af0;">
                        <span><i class="ph-info me-2"></i>Informasi Akses</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="profile-avatar">
                                <i class="ph-user"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Selamat datang, {{ $apiUser->name }}!</h5>
                                <p class="text-muted mb-3" style="font-size: .88rem; line-height: 1.7;">
                                    Akun Anda terdaftar sebagai
                                    <strong>{{ ucfirst($role) }}</strong>.
                                    Dashboard khusus (pengajuan usulan, monitoring proposal, dll.)
                                    saat ini hanya tersedia untuk peran
                                    <strong>Dosen</strong>, <strong>Reviewer</strong>, dan <strong>Admin</strong>.
                                    <br>Jika Anda memerlukan akses tambahan, silakan hubungi admin LPPM.
                                </p>
                                <span class="badge rounded-pill px-3 py-2"
                                    style="background:#f3f0ff; color:#7f5af0; font-size:.72rem; font-weight:700;">
                                    <i class="ph-lock me-1"></i>
                                    Akses terbatas — {{ ucfirst($role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($role === 'operator')
        {{-- OPERATOR VIEW (tetap sama) --}}
        <div class="d-flex align-items-center mb-4">
            <div class="icon-box-modern accent-indigo me-3" style="margin-bottom:0;"><i
                    class="ph-bold ph-sliders"></i></div>
            <div>
                <h3 class="fw-bold text-dark mb-0">Panel Operator</h3>
                <p class="text-muted mb-0">Halo {{ $apiUser?->name ?? 'Operator' }}, kelola data master sistem LPPM.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-cyan"><i class="ph-bold ph-list-numbers"></i></div><span
                            class="stat-count" style="color:#0dcaf0;">Terdaftar: {{ $total_indeks ?? 0 }}</span>
                        <h2 class="stat-title-text">Data Indeks</h2>
                        <p class="stat-desc-text">Manajemen database indeks publikasi peneliti (Scopus, Sinta, dll).
                        </p><a href="{{ route('operator.indeks') }}" class="btn-modern btn-cyan">Atur Indeks <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-purple"><i class="ph-bold ph-graduation-cap"></i></div>
                        <span class="stat-count" style="color:#6f42c1;">Fakultas: {{ $total_fakultas ?? 0 }}</span>
                        <h2 class="stat-title-text">Data Fakultas</h2>
                        <p class="stat-desc-text">Kelola data induk fakultas yang ada di lingkungan kampus IT Del.</p>
                        <a href="{{ route('operator.fakultas') }}" class="btn-modern btn-purple">Detail Fakultas <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-pink"><i class="ph-bold ph-books"></i></div><span
                            class="stat-count" style="color:#d63384;">Prodi: {{ $total_prodi ?? 0 }}</span>
                        <h2 class="stat-title-text">Program Studi</h2>
                        <p class="stat-desc-text">Manajemen daftar Program Studi untuk setiap fakultas yang terdaftar.
                        </p><a href="{{ route('operator.prodi') }}" class="btn-modern btn-pink">Atur Prodi <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-orange"><i class="ph-bold ph-file-text"></i></div><span
                            class="stat-count" style="color:#fd7e14;">Status: Monitoring</span>
                        <h2 class="stat-title-text">Manajemen Proposal</h2>
                        <p class="stat-desc-text">Pantau usulan, skema kriteria, dan pembukaan periode proposal.</p><a
                            href="{{ route('operator.kategori') }}" class="btn-modern btn-orange">Monitor Usulan <i
                                class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-green"><i class="ph-bold ph-coins"></i></div><span
                            class="stat-count" style="color:#0070C0;">Keuangan</span>
                        <h2 class="stat-title-text">Anggaran (RAB)</h2>
                        <p class="stat-desc-text">Kelola kategori biaya dan standar pagu anggaran usulan penelitian.
                        </p><a href="{{ route('operator.kategori-biaya') }}" class="btn-modern btn-green">Atur Biaya
                            <i class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card stat-card-custom h-100">
                    <div class="card-body p-4">
                        <div class="icon-box-modern accent-indigo"><i class="ph-bold ph-user-list"></i></div><span
                            class="stat-count" style="color:#3f51b5;">Akses</span>
                        <h2 class="stat-title-text">Manajemen Reviewer</h2>
                        <p class="stat-desc-text">Kelola akun dan hak akses dosen yang bertindak sebagai reviewer
                            usulan.</p><a href="{{ route('operator.reviewer') }}" class="btn-modern btn-indigo">Atur
                            Reviewer <i class="ph-bold ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>

        </div>
        @php
            $chartOperator = [
                ['label' => 'Fakultas', 'total' => $total_fakultas ?? 0],
                ['label' => 'Prodi', 'total' => $total_prodi ?? 0],
                ['label' => 'Indeks', 'total' => $total_indeks ?? 0],
                ['label' => 'Proposal', 'total' => \App\Models\Proposal::count()],
                ['label' => 'Reviewer', 'total' => \App\Models\ProposalReviewer::distinct('reviewer_id')->count()],
            ];
        @endphp
        <div class="d-flex align-items-center mb-3 mt-4">
            <i class="ph-bold ph-chart-pie me-2 text-primary" style="font-size:1.3rem;"></i>
            <h5 class="fw-bold text-dark mb-0">Ringkasan Visual Data</h5>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
                    <div class="card-header py-3 px-4" style="background:#0c2461;border:none;">
                        <span class="fw-bold text-white" style="font-size:.88rem;">
                            <i class="ph-chart-pie me-2 text-warning"></i>Ringkasan Data Operator
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center p-4">
                        <div style="position:relative;width:280px;height:280px;">
                            <canvas id="chartOperator"></canvas>
                        </div>
                        <div class="mt-3 w-100">
                            @foreach ($chartOperator as $idx => $item)
                                @php
                                    $colors = ['#6f42c1', '#00bcd4', '#ff9800', '#f44336', '#4caf50'];
                                    $c = $colors[$idx % count($colors)];
                                @endphp
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span style="font-size:.78rem;">
                                        <span class="d-inline-block me-1"
                                            style="width:10px;height:10px;border-radius:50%;background:{{ $c }};"></span>
                                        {{ $item['label'] }}
                                    </span>
                                    <span class="fw-bold" style="font-size:.78rem;">{{ $item['total'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const operatorData = @json($chartOperator);
                const operatorColors = ['#6f42c1', '#00bcd4', '#ff9800', '#f44336', '#4caf50'];
                new Chart(document.getElementById('chartOperator'), {
                    type: 'doughnut',
                    data: {
                        labels: operatorData.map(d => d.label),
                        datasets: [{
                            data: operatorData.map(d => d.total),
                            backgroundColor: operatorColors,
                            borderWidth: 2,
                            borderColor: '#fff',
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 800
                        }
                    }
                });
            });
        </script>
    @endif
</div>
