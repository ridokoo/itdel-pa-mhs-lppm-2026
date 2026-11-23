@php
    $apiUser = request()->auth ?? auth()->user();
    $role = strtolower($apiUser->role ?? 'guest');
    $isDosen = in_array($role, ['dosen', 'reviewer', 'staff']);

    $user = auth()->user();
    $myRoles = $user ? $user->roles->pluck('name')->map(fn($n) => strtolower($n))->toArray() : [];
    $activeRole = session('active_role', !empty($myRoles) ? $myRoles[0] : $role);

    if ($isDosen) {
        $navTheme = 'navbar-dark shadow-sm';
        $navStyle = 'background-color: #0070C0 !important;';
    } else {
        $navTheme = 'navbar-light bg-white border-bottom shadow-sm';
        $navStyle = 'background-color: #ffffff !important;';
    }

    $notifService = app(\App\Services\NotificationService::class);
    $notifications = auth()->check() ? $notifService->getNotifications(auth()->id()) : [];
    $allNotifs = $notifications;
    $notifCount = collect($notifications)->where('read_at', null)->count();

    $kategoriPenelitian = \App\Models\Category::where('name', 'like', '%penelitian%')->first();
    $kategoriPengabdian = \App\Models\Category::where('name', 'like', '%pengabdian%')->first();

    $urlUsulanPenelitian = $kategoriPenelitian
        ? route('dosen.usulan.index', ['categoryId' => $kategoriPenelitian->id])
        : '#';
    $urlLaporanPenelitian = $kategoriPenelitian
        ? route('dosen.laporan-kemajuan.index', ['categoryId' => $kategoriPenelitian->id])
        : '#';
    $urlLaporanAkhirPenelitian = $kategoriPenelitian
        ? route('dosen.laporan-akhir.index.category', ['categoryId' => $kategoriPenelitian->id])
        : route('dosen.laporan-akhir.index');

    $urlUsulanPengabdian = $kategoriPengabdian
        ? route('dosen.usulan.index', ['categoryId' => $kategoriPengabdian->id])
        : '#';
    $urlLaporanPengabdian = $kategoriPengabdian
        ? route('dosen.laporan-kemajuan.index', ['categoryId' => $kategoriPengabdian->id])
        : '#';
    $urlLaporanAkhirPengabdian = $kategoriPengabdian
        ? route('dosen.laporan-akhir.index.category', ['categoryId' => $kategoriPengabdian->id])
        : route('dosen.laporan-akhir.index');
@endphp

@if ($isDosen)
    <div class="navbar navbar-expand-lg {{ $navTheme }} custom-navbar" style="{{ $navStyle }}">
        <div class="container-fluid">

            {{-- Logo --}}
            <a href="{{ route('index') }}" class="navbar-brand d-inline-flex align-items-center">
                <img src="{{ asset('assets/images/LPPM.jpeg') }}" class="h-32px" alt="Logo">
                <span class="ms-2 fw-bold text-white fs-4 tracking-tight">LPPM IT DEL</span>
            </a>

            {{-- Toggler Mobile --}}
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarDosen">
                <i class="ph-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarDosen">
                <div class="d-flex w-100 align-items-center justify-content-between">

                    {{-- Menu Tengah --}}
                    <ul class="nav navbar-nav mx-auto gap-1">

                        {{-- Dashboard --}}
                        <li class="nav-item">
                            <a href="{{ route('index') }}"
                                class="navbar-nav-link rounded text-white {{ request()->routeIs('index') ? 'active-bima' : '' }}">
                                <i class="ph-gauge me-2"></i> Dashboard
                            </a>
                        </li>

                        @if ($activeRole == 'dosen')
                            {{-- PENELITIAN --}}
                            <li class="nav-item dropdown">
                                <a href="#" id="dropdownPenelitian"
                                    class="navbar-nav-link rounded text-white {{ request()->routeIs('dosen.usulan.*') && request()->route('categoryId') == $kategoriPenelitian?->id ? 'active-bima' : '' }}">
                                    <i class="ph-microscope me-2"></i> Penelitian
                                    <i class="ph-caret-down ms-1" style="font-size:11px;"></i>
                                </a>
                                <ul class="dropdown-menu shadow-lg border-0 mt-1" id="menuPenelitian">
                                    <li>
                                        <a href="{{ $urlUsulanPenelitian }}"
                                            class="dropdown-item {{ request()->routeIs('dosen.usulan.*') && request()->route('categoryId') == $kategoriPenelitian?->id ? 'active' : '' }}">
                                            <i class="ph-file-plus me-2 text-primary"></i> Usulan Baru
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ $urlLaporanPenelitian }}"
                                            class="dropdown-item {{ request()->routeIs('dosen.laporan-kemajuan.*') ? 'active' : '' }}">
                                            <i class="ph-file-text me-2 text-success"></i> Laporan Kemajuan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ $urlLaporanAkhirPenelitian }}"
                                            class="dropdown-item {{ request()->routeIs('dosen.laporan-akhir.*') ? 'active' : '' }}">
                                            <i class="ph-file-check me-2 text-warning"></i> Laporan Akhir
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            {{-- PENGABDIAN --}}
                            <li class="nav-item dropdown">
                                <a href="#" id="dropdownPengabdian"
                                    class="navbar-nav-link rounded text-white {{ request()->routeIs('dosen.usulan.*') && request()->route('categoryId') == $kategoriPengabdian?->id ? 'active-bima' : '' }}">
                                    <i class="ph-users-four me-2"></i> Pengabdian
                                    <i class="ph-caret-down ms-1" style="font-size:11px;"></i>
                                </a>
                                <ul class="dropdown-menu shadow-lg border-0 mt-1" id="menuPengabdian">
                                    <li>
                                        <a href="{{ $urlUsulanPengabdian }}"
                                            class="dropdown-item {{ request()->routeIs('dosen.usulan.*') && request()->route('categoryId') == $kategoriPengabdian?->id ? 'active' : '' }}">
                                            <i class="ph-file-plus me-2 text-primary"></i> Usulan Baru
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ $urlLaporanPengabdian }}"
                                            class="dropdown-item {{ request()->routeIs('dosen.laporan-kemajuan.*') ? 'active' : '' }}">
                                            <i class="ph-file-text me-2 text-success"></i> Laporan Kemajuan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ $urlLaporanAkhirPengabdian }}"
                                            class="dropdown-item {{ request()->routeIs('dosen.laporan-akhir.*') ? 'active' : '' }}">
                                            <i class="ph-file-check me-2 text-warning"></i> Laporan Akhir
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @elseif ($activeRole == 'reviewer')
                            <li class="nav-item">
                                <a href="{{ route('reviewer.dashboard') }}"
                                    class="navbar-nav-link rounded text-white {{ request()->routeIs('reviewer.dashboard') ? 'active-bima' : '' }}">
                                    <i class="ph-signature me-2"></i> Tugas Review Proposal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reviewer.laporan.index') }}"
                                    class="navbar-nav-link rounded text-white {{ request()->routeIs('reviewer.laporan.*') ? 'active-bima' : '' }}">
                                    <i class="ph-file-text me-2"></i> Tugas Review Laporan Kemajuan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reviewer.laporan-akhir.index') }}"
                                    class="navbar-nav-link rounded text-white {{ request()->routeIs('reviewer.laporan-akhir.*') ? 'active-bima' : '' }}">
                                    <i class="ph-check-square-offset me-2"></i> Review Lap. Akhir
                                </a>
                            </li>
                        @endif

                    </ul>

                    {{-- Sisi Kanan --}}
                    <ul class="nav navbar-nav align-items-center gap-2 flex-shrink-0">

                        {{-- Notifikasi --}}
                        <li class="nav-item">
                            <a href="#"
                                class="navbar-nav-link navbar-nav-link-icon rounded-pill text-white position-relative"
                                data-bs-toggle="offcanvas" data-bs-target="#notifications">
                                <i class="ph-bell"></i>
                                @if ($notifCount > 0)
                                    <span
                                        class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle rounded-pill"
                                        style="font-size:0.65rem; padding:0.25em 0.5em;">
                                        {{ $notifCount }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        {{-- Switch Role --}}
                        @if (count($myRoles) > 1)
                            <li class="nav-item dropdown">
                                <a href="#" id="dropdownSwitchRole"
                                    class="navbar-nav-link d-flex align-items-center rounded-pill px-3 py-1 border border-white border-opacity-30">
                                    <i class="ph-swap me-2 text-white"></i>
                                    <span
                                        class="text-white fw-semibold fs-sm text-uppercase">{{ $activeRole }}</span>
                                    <i class="ph-caret-down ms-1 text-white fs-xs opacity-50"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-lg-2"
                                    id="menuSwitchRole">
                                    <li>
                                        <div class="dropdown-header text-uppercase fs-xs fw-bold">Ganti Peran</div>
                                    </li>
                                    @foreach ($myRoles as $r)
                                        <li>
                                            <a href="{{ route('auth.switch-role', ['role' => $r]) }}"
                                                class="dropdown-item {{ $activeRole == $r ? 'active' : '' }}">
                                                <i class="ph-user-circle me-2"></i> {{ strtoupper($r) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        {{-- Profil --}}
                        <li class="nav-item dropdown">
                            <a href="#" id="dropdownProfil"
                                class="navbar-nav-link d-flex align-items-center rounded-pill p-1 pe-3 border border-white border-opacity-20 ms-lg-1">
                                <img src="{{ $apiUser->photo ?? asset('assets/images/demo/users/face11.jpg') }}"
                                    class="w-32px h-32px rounded-pill border border-white border-opacity-50"
                                    alt="" />
                                <span class="d-none d-lg-inline-block mx-2 text-white fw-semibold fs-sm">
                                    {{ $apiUser->name ?? 'User' }}
                                </span>
                                <i class="ph-caret-down text-white fs-xs opacity-50"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-xl border-0 mt-lg-2" id="menuProfil">
                                <li>
                                    <div class="dropdown-header d-flex align-items-center p-3">
                                        <div
                                            class="bg-success bg-opacity-10 text-success fw-bold rounded-pill px-3 py-1 text-uppercase fs-xs">
                                            {{ $activeRole }}
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a href="{{ route('dosen.profil') }}"
                                        class="dropdown-item py-2 {{ request()->routeIs('dosen.profil') ? 'active' : '' }}">
                                        <i class="ph-user-focus me-2 text-muted"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider opacity-50">
                                </li>
                                <li>
                                    <a href="{{ route('auth.logout') }}"
                                        class="dropdown-item py-2 text-danger fw-bold">
                                        <i class="ph-sign-out me-2"></i> Keluar
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>

                </div> {{-- tutup d-flex w-100 --}}
            </div> {{-- tutup collapse --}}
        </div> {{-- tutup container-fluid --}}
    </div> {{-- tutup navbar --}}
@else
    {{-- NAVBAR OPERATOR / ADMIN --}}
    <div class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm custom-navbar"
        style="background-color: #ffffff !important;">
        <div class="container-fluid">

            <div class="d-flex align-items-center">
                <button type="button" id="mobileSidebarToggle"
                    class="btn btn-icon rounded-pill me-2 d-inline-flex d-lg-none"
                    style="color: #0070C0; border: 1px solid #0070C0;">
                    <i class="ph-bold ph-list" style="font-size: 18px;"></i>
                </button>
                <a href="{{ route('index') }}" class="navbar-brand d-inline-flex align-items-center">
                    <img src="{{ asset('assets/images/LPPM.jpeg') }}" class="h-32px" alt="Logo">
                    <span class="ms-2 fw-bold text-dark fs-4 tracking-tight">LPPM IT DEL</span>
                </a>
            </div>

            <div class="navbar-collapse show" id="navbar_main_content">
                <ul class="nav navbar-nav ms-lg-auto align-items-center gap-2">

                    @php $unreadCount = collect($allNotifs)->where('read_at', null)->count(); @endphp
                    <li class="nav-item">
                        <a href="#"
                            class="navbar-nav-link navbar-nav-link-icon rounded-pill text-dark position-relative"
                            data-bs-toggle="offcanvas" data-bs-target="#notifications">
                            <i class="ph-bell"></i>
                            @if ($unreadCount > 0)
                                <span
                                    class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle rounded-pill"
                                    style="font-size:0.65rem; padding:0.25em 0.5em;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" id="dropdownProfil"
                            class="navbar-nav-link d-flex align-items-center rounded-pill p-1 pe-3 border border-dark border-opacity-10 ms-lg-2">
                            <img src="{{ $apiUser->photo ?? asset('assets/images/demo/users/face11.jpg') }}"
                                class="w-32px h-32px rounded-pill" alt="" />
                            <span class="d-none d-lg-inline-block mx-2 text-dark fw-semibold fs-sm">
                                {{ $apiUser->name ?? 'User' }}
                            </span>
                            <i class="ph-caret-down text-dark fs-xs opacity-50"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-xl border-0 mt-lg-2" id="menuProfil">
                            <li>
                                <div class="dropdown-header d-flex align-items-center p-3">
                                    <div
                                        class="bg-primary bg-opacity-10 text-primary fw-bold rounded-pill px-3 py-1 text-uppercase fs-xs">
                                        {{ $role }}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('app.profile') }}" class="dropdown-item py-2">
                                    <i class="ph-user-circle me-2 text-muted"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider opacity-50">
                            </li>
                            <li>
                                <a href="{{ route('auth.logout') }}" class="dropdown-item py-2 text-danger fw-bold">
                                    <i class="ph-sign-out me-2"></i> Keluar
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
@endif

<style>
    .custom-navbar {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .active-bima {
        background-color: rgba(255, 255, 255, 0.15) !important;
        border-bottom: 3px solid #ffb400 !important;
        border-radius: 6px 6px 0 0 !important;
        font-weight: 600;
    }

    .navbar-nav-link {
        font-size: 14px;
        font-weight: 500;
        padding: 0.6rem 1rem !important;
        transition: all 0.25s ease;
    }

    .navbar-nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    .navbar .nav-item.dropdown .dropdown-menu {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 12px !important;
        padding: 8px !important;
        min-width: 220px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .dropdown-item {
        border-radius: 8px !important;
        padding: 10px 15px !important;
        color: #334155 !important;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background-color: #f1f5f9 !important;
        color: #0070C0 !important;
    }

    .dropdown-item.active {
        background-color: #e6f9f4 !important;
        color: #0070C0 !important;
        font-weight: 600 !important;
    }
</style>

<script>
    (function() {
        var dropdownMap = [{
                triggerId: 'dropdownPenelitian',
                menuId: 'menuPenelitian'
            },
            {
                triggerId: 'dropdownPengabdian',
                menuId: 'menuPengabdian'
            },
            {
                triggerId: 'dropdownProfil',
                menuId: 'menuProfil'
            },
            {
                triggerId: 'dropdownSwitchRole',
                menuId: 'menuSwitchRole'
            },
        ];

        function closeAll() {
            dropdownMap.forEach(function(d) {
                var menu = document.getElementById(d.menuId);
                if (menu) menu.classList.remove('show');
            });
        }

        function setupDropdowns() {
            dropdownMap.forEach(function(d) {
                var trigger = document.getElementById(d.triggerId);
                var menu = document.getElementById(d.menuId);
                if (!trigger || !menu) return;

                var newTrigger = trigger.cloneNode(true);
                trigger.parentNode.replaceChild(newTrigger, trigger);

                newTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    var isOpen = menu.classList.contains('show');
                    closeAll();
                    if (!isOpen) menu.classList.add('show');
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar')) closeAll();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupDropdowns);
        } else {
            setupDropdowns();
        }

        window.addEventListener('load', setupDropdowns);
        document.addEventListener('livewire:navigated', setupDropdowns);
        document.addEventListener('livewire:updated', setupDropdowns);
    })();
</script>
