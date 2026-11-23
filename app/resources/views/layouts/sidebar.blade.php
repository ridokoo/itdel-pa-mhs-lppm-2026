<!-- Main sidebar -->
<div class="sidebar sidebar-light sidebar-main sidebar-expand-lg"
    style="background-color: #0070C0 !important; border-right: none;">



    <!-- Sidebar header -->


    <!-- Sidebar content -->
    <div class="sidebar-content">
        @php
            $user = auth()->user();
            // 1. Ambil semua role dalam array (lowercase)
            $myRoles = $user ? $user->roles->pluck('name')->map(fn($n) => strtolower($n))->toArray() : [];

            // 2. Ambil peran aktif dari session, default ke role pertama
            $activeRole = session('active_role', !empty($myRoles) ? $myRoles[0] : 'guest');

            // 3. Definisikan $role agar tidak error di baris bawah
            $role = $activeRole;

            // 4. Data dari API (Foto & Profile)
            $apiUser = request()->auth;
        @endphp

        <!-- Profil Singkat & Switch Role Dropdown -->
        <div class="sidebar-section sidebar-resize-hide pt-2">
            <div
                class="mx-3 mb-3 p-2 bg-black bg-opacity-10 rounded-pill border border-white border-opacity-10 dropdown">
                <a href="#" class="hstack gap-2 text-white text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <img src="{{ $apiUser->photo ?? asset('assets/images/demo/users/face11.jpg') }}"
                        class="w-32px h-32px rounded-pill border border-white border-opacity-20 shadow-sm"
                        alt="" />
                    <div class="me-auto overflow-hidden">
                        <div class="fs-xs text-white opacity-70 lh-1 mb-1">Logged in as:</div>
                        <div class="fw-bold text-white fs-sm truncate text-uppercase">{{ ucwords($role) }}</div>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-dark w-100 shadow-lg border-secondary">
                    <div class="dropdown-header text-uppercase fs-xs fw-bold">Ganti Peran Anda</div>
                    @foreach ($myRoles as $r)
                        <a href="{{ route('auth.switch-role', ['role' => $r]) }}"
                            class="dropdown-item {{ $activeRole == $r ? 'active' : '' }}">
                            <i class="ph-user-circle me-2"></i> {{ strtoupper($r) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Navigasi -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- DASHBOARD -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-xs fw-bold lh-sm text-white opacity-50 sidebar-resize-hide">Menu Utama
                    </div>
                    <i class="ph-dots-three sidebar-resize-show text-white"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('index') }}" class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}">
                        <i class="ph-bold ph-gauge"></i> <span>Dashboard</span>
                    </a>
                </li>

                {{-- ========================================== --}}
                {{-- MODE DOSEN --}}
                {{-- ========================================== --}}
                @if ($activeRole == 'dosen')
                    @php
                        $allCategories = \App\Models\Category::orderBy('name')->get();
                        $kategoriPenelitian = $allCategories->first(
                            fn($c) => str_contains(strtolower($c->name), 'penelitian'),
                        );
                        $kategoriPengabdian = $allCategories->first(
                            fn($c) => str_contains(strtolower($c->name), 'pengabdian'),
                        );
                    @endphp

                    <li class="nav-item-header">
                        <div class="text-uppercase fs-xs fw-bold text-white opacity-50 sidebar-resize-hide">Aktivitas
                            Saya</div>
                    </li>

                    <li
                        class="nav-item nav-item-submenu {{ request()->is('app/dosen/usulan/*') ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="ph-bold ph-microscope"></i>
                            <span>Penelitian</span>
                        </a>
                        <ul class="nav-group-sub collapse {{ request()->is('app/dosen/usulan/*') ? 'show' : '' }}">
                            <li class="nav-item">
                                <a href="{{ $kategoriPenelitian ? route('dosen.usulan.index', ['categoryId' => $kategoriPenelitian->id]) : '#' }}"
                                    class="nav-link {{ $kategoriPenelitian && request()->fullUrlIs(route('dosen.usulan.index', ['categoryId' => $kategoriPenelitian->id])) ? 'active' : '' }}">
                                    Usulan Baru
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Laporan Kemajuan</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Laporan Akhir</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link">
                            <i class="ph-bold ph-users-four"></i>
                            <span>Pengabdian</span>
                        </a>
                        <ul class="nav-group-sub collapse">
                            <li class="nav-item">
                                <a href="{{ $kategoriPengabdian ? route('dosen.usulan.index', ['categoryId' => $kategoriPengabdian->id]) : '#' }}"
                                    class="nav-link {{ $kategoriPengabdian && request()->fullUrlIs(route('dosen.usulan.index', ['categoryId' => $kategoriPengabdian->id])) ? 'active' : '' }}">
                                    Usulan Baru
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Laporan Kemajuan</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Laporan Akhir</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('dosen.profil') }}"
                            class="nav-link {{ request()->routeIs('dosen.profil') ? 'active' : '' }}">
                            <i class="ph-bold ph-user-focus"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                @endif

                {{-- ========================================== --}}
                {{-- MODE REVIEWER --}}
                {{-- ========================================== --}}
                @if ($activeRole == 'reviewer')
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-xs fw-bold text-white opacity-50">Reviewer Area</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reviewer.dashboard') }}"
                            class="nav-link {{ request()->routeIs('reviewer.dashboard') ? 'active' : '' }}">
                            <i class="ph-bold ph-signature text-warning"></i>
                            <span>Tugas Review Proposal</span>
                        </a>
                    </li>
                @endif

                {{-- ========================================== --}}
                {{-- MODE ADMIN LPPM --}}
                {{-- ========================================== --}}
                @if ($activeRole == 'admin lppm')
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-xs fw-bold lh-sm text-white opacity-50 sidebar-resize-hide">Konten
                            Guest</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.beranda') }}"
                            class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                            <i class="ph-bold ph-house"></i> <span>Kelola Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.gallery') }}"
                            class="nav-link {{ request()->routeIs('admin.gallery') ? 'active' : '' }}">
                            <i class="ph-bold ph-image"></i> <span>Kelola Galeri</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.berita') }}"
                            class="nav-link {{ request()->routeIs('admin.berita') ? 'active' : '' }}">
                            <i class="ph-bold ph-newspaper"></i> <span>Kelola Berita</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pengumuman') }}"
                            class="nav-link {{ request()->routeIs('admin.pengumuman') ? 'active' : '' }}">
                            <i class="ph-bold ph-megaphone"></i> <span>Kelola Pengumuman</span>
                        </a>
                    </li>
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-xs fw-bold lh-sm text-white opacity-50 sidebar-resize-hide">
                            Dokumen & Kontak</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kategori-dokumen') }}"
                            class="nav-link {{ request()->routeIs('admin.kategori-dokumen') ? 'active' : '' }}">
                            <i class="ph-bold ph-folders"></i> <span>Kategori Dokumen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dokumen') }}"
                            class="nav-link {{ request()->routeIs('admin.dokumen') ? 'active' : '' }}">
                            <i class="ph-bold ph-file-text"></i> <span>Daftar Dokumen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kontak') }}"
                            class="nav-link {{ request()->routeIs('admin.kontak') ? 'active' : '' }}">
                            <i class="ph-bold ph-address-book"></i> <span>Kelola Kontak</span>
                        </a>
                    </li>

                    {{-- Tambahkan menu lain sesuai kebutuhan admin lppm --}}
                @endif







                {{-- ========================================== --}}
                {{-- 5. MODE KETUA LPPM --}}
                {{-- ========================================== --}}
                @if ($activeRole == 'ketua lppm')
                    {{-- Pastikan string ini sama dengan yang di database/session --}}
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-xs fw-bold text-white opacity-50">Manajemen Kebijakan</div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('ketua.dashboard') }}"
                            class="nav-link {{ request()->routeIs('ketua.dashboard') ? 'active' : '' }}">
                            <i class="ph-bold ph-seal-check text-warning"></i>
                            <span>Persetujuan Final Usulan</span>
                        </a>
                    </li>
                @endif

                {{-- ========================================== --}}
                {{-- AREA OPERATOR --}}
                {{-- ========================================== --}}
                @if ($role == 'operator')
                    @php
                        $activeParent = '';
                        if (
                            request()->is('app/operator/indeks*') ||
                            request()->is('app/operator/fakultas*') ||
                            request()->is('app/operator/prodi*') ||
                            request()->is('app/operator/jabatan-fungsional*')
                        ) {
                            $activeParent = 'akademik';
                        } elseif (
                            request()->is('app/operator/kategori*') ||
                            request()->is('app/operator/kelompok-skema*') ||
                            request()->is('app/operator/tipe-skema*') ||
                            request()->is('app/operator/skemakriteria*') ||
                            request()->is('app/operator/proposalperiod*') ||
                            request()->is('app/operator/status-proposal*')
                        ) {
                            $activeParent = 'proposal';
                        } elseif (
                            request()->is('app/operator/target-tkt*') ||
                            request()->is('app/operator/output-target*') ||
                            request()->is('app/operator/luaran-skema*')
                        ) {
                            $activeParent = 'target';
                        }
                    @endphp

                    <li class="nav-item-header">
                        <div class="text-uppercase fs-xs fw-bold lh-sm text-white opacity-50 sidebar-resize-hide">
                            Operator Master</div>
                    </li>

                    {{-- 1. AKADEMIK --}}
                    <li
                        class="nav-item nav-item-submenu {{ $activeParent === 'akademik' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $activeParent === 'akademik' ? 'nav-parent-active' : '' }}">
                            <i class="ph-bold ph-graduation-cap"></i> <span>Manajemen Data Akademik</span>
                        </a>
                        <ul class="nav-group-sub collapse {{ $activeParent === 'akademik' ? 'show' : '' }}">
                            <li class="nav-item"><a href="{{ route('operator.indeks') }}"
                                    class="nav-link {{ request()->routeIs('operator.indeks') ? 'active' : '' }}">Data
                                    Indeks</a></li>
                            <li class="nav-item"><a href="{{ route('operator.fakultas') }}"
                                    class="nav-link {{ request()->routeIs('operator.fakultas') ? 'active' : '' }}">Fakultas</a>
                            </li>
                            <li class="nav-item"><a href="{{ route('operator.prodi') }}"
                                    class="nav-link {{ request()->routeIs('operator.prodi') ? 'active' : '' }}">Program
                                    Studi</a></li>
                            <li class="nav-item"><a href="{{ route('operator.jabatan-fungsional') }}"
                                    class="nav-link {{ request()->routeIs('operator.jabatan-fungsional') ? 'active' : '' }}">Jabatan
                                    Fungsional</a></li>
                        </ul>
                    </li>

                    {{-- 2. PROPOSAL --}}
                    <li
                        class="nav-item nav-item-submenu {{ $activeParent === 'proposal' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $activeParent === 'proposal' ? 'nav-parent-active' : '' }}">
                            <i class="ph-bold ph-file-text"></i> <span>Manajemen Proposal</span>
                        </a>
                        <ul class="nav-group-sub collapse {{ $activeParent === 'proposal' ? 'show' : '' }}">
                            <li class="nav-item"><a href="{{ route('operator.kategori') }}"
                                    class="nav-link {{ request()->routeIs('operator.kategori') ? 'active' : '' }}">Kategori</a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('operator.proposalperiod') }}"
                                    class="nav-link {{ request()->routeIs('operator.proposalperiod') ? 'nav-child-active' : '' }}">
                                    Periode Proposal

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.target-tkt') }}"
                                    class="nav-link {{ request()->routeIs('operator.target-tkt') ? 'nav-child-active' : '' }}">
                                    Target TKT

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.kelompok-skema') }}"
                                    class="nav-link {{ request()->routeIs('operator.kelompok-skema') ? 'nav-child-active' : '' }}">
                                    Kelompok Skema
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.tipe-skema') }}"
                                    class="nav-link {{ request()->routeIs('operator.tipe-skema') ? 'nav-child-active' : '' }}">
                                    Tipe Skema
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('operator.scopes') }}"
                                    class="nav-link {{ request()->routeIs('operator.scopes') ? 'nav-child-active' : '' }}">
                                    Ruang Lingkup

                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('operator.skemakriteria') }}"
                                    class="nav-link {{ request()->routeIs('operator.skemakriteria') ? 'nav-child-active' : '' }}">
                                    Detail Kriteria

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.luaran-skema') }}"
                                    class="nav-link {{ request()->routeIs('operator.luaran-skema') ? 'nav-child-active' : '' }}">
                                    Detail Kategori Luaran

                                </a>
                            </li>

                        </ul>
                    </li>

                    {{-- 3. TARGET LUARAN --}}
                    <li
                        class="nav-item nav-item-submenu {{ $activeParent === 'target' ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ $activeParent === 'target' ? 'nav-parent-active' : '' }}">
                            <i class="ph-bold ph-medal"></i> <span>Manajemen Luaran</span>
                        </a>
                        <ul class="nav-group-sub collapse {{ $activeParent === 'target' ? 'show' : '' }}">

                            <li class="nav-item">
                                <a href="{{ route('operator.output-target-type') }}"
                                    class="nav-link {{ request()->routeIs('operator.output-target-type') ? 'nav-child-active' : '' }}">
                                    Jenis Target Luaran

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.output-target-category') }}"
                                    class="nav-link {{ request()->routeIs('operator.output-target-category') ? 'nav-child-active' : '' }}">
                                    Kategori Target Luaran

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.output-target-detail') }}"
                                    class="nav-link {{ request()->routeIs('operator.output-target-detail') ? 'nav-child-active' : '' }}">
                                    Detail Target Luaran

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.output-target-status') }}"
                                    class="nav-link {{ request()->routeIs('operator.output-target-status') ? 'nav-child-active' : '' }}">
                                    Status Target Luaran

                                </a>
                            </li>



                        </ul>
                    </li>

                    {{-- 4. KATEGORI BIAYA --}}
                    <li class="nav-item">
                        <a href="{{ route('operator.kategori-biaya') }}"
                            class="nav-link {{ request()->routeIs('operator.kategori-biaya') ? 'active' : '' }}">
                            <i class="ph-bold ph-currency-dollar"></i> <span>Kategori Biaya (RAB)</span>
                        </a>
                    </li>

                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link {{ $activeParent === 'status' ? 'active' : '' }}">
                            <i class="ph-bold ph-flag"></i>
                            <span>Manajemen Status</span>
                        </a>

                        <ul class="nav-group-sub collapse {{ $activeParent === 'status' ? 'show' : '' }}">

                            <li class="nav-item">
                                <a href="{{ route('operator.status-proposal') }}"
                                    class="nav-link {{ request()->routeIs('operator.status-proposal') ? 'nav-child-active' : '' }}">
                                    Status Usulan
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('operator.member-status') }}"
                                    class="nav-link {{ request()->routeIs('operator.member-status') ? 'nav-child-active' : '' }}">
                                    Status Anggota Usulan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('operator.status-final-usulan') }}"
                                    class="nav-link {{ request()->routeIs('operator.status-final-usulan') ? 'nav-child-active' : '' }}">
                                    Status Usulan Final
                                </a>
                            </li>

                        </ul>
                    </li>

                    {{-- 5. REVIEWER --}}
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link {{ $activeParent === 'reviewer' ? 'active' : '' }}">
                            <i class="ph-bold ph-user-list"></i>
                            <span>Manajemen Reviewer</span>
                        </a>

                        <ul class="nav-group-sub collapse {{ $activeParent === 'reviewer' ? 'show' : '' }}">

                            <li class="nav-item">
                                <a href="{{ route('operator.reviewer') }}"
                                    class="nav-link {{ request()->routeIs('operator.reviewer') ? 'nav-child-active' : '' }}">
                                    Kelola Reviewer
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('operator.assign-reviewer') }}"
                                    class="nav-link {{ request()->routeIs('operator.assign-reviewer') ? 'nav-child-active' : '' }}">
                                    Penugasan Reviewer
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('operator.status-reviewer') }}"
                                    class="nav-link {{ request()->routeIs('operator.status-reviewer') ? 'nav-child-active' : '' }}">
                                    Status Reviewer
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                <!-- LOGOUT -->
                <li class="nav-item-divider opacity-10"></li>

                <li class="nav-item">
                    <a href="{{ route('auth.logout') }}" class="nav-link text-white">
                        <i class="ph-bold ph-sign-out text-danger"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>



