<!DOCTYPE html> <!-- WAJIB ADA DI BARIS NOMOR 1 -->
<html lang="id">

<head>
    @livewireStyles

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>LPPM - IT DEL @yield('title')</title>
<link rel="shortcut icon" href="{{ asset('img/del.png') }}">
<link rel="icon" type="image/png" href="{{ asset('img/del.png') }}">
    @php
        $authUser = request()->auth ?? auth()->user();
        $authRole = strtolower($authUser->role ?? 'guest');
        $isDosen = in_array($authRole, ['dosen', 'reviewer', 'staff']);
    @endphp

    <!-- Phosphor modern -->
    <!-- CSS -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/images/icons/phosphor/styles.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" rel="stylesheet" />

    <!-- ICON -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Additional styles -->
    @yield('styles')

    <style>
        /* ================= SIDEBAR BASE ================= */
        .sidebar,
        .sidebar-content,
        .sidebar-section {
            background-color: #0070C0 !important;
            border-right: none !important;
        }

        .sidebar .nav-link,
        .sidebar .nav-link i,
        .sidebar .nav-item-header div {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
        }

        .sidebar .nav-link.nav-parent-active,
        .sidebar .nav-item-submenu.nav-item-open > .nav-link,
        .sidebar .nav-item-submenu.nav-item-expanded > .nav-link {
            background-color: rgba(0, 0, 0, 0.20) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border-radius: 8px 8px 0 0 !important;
            margin: 0 !important;
            padding-right: 1rem !important;
            box-shadow: none !important;
        }

        .sidebar .nav-link.nav-parent-active i,
        .sidebar .nav-item-submenu.nav-item-open > .nav-link i,
        .sidebar .nav-item-submenu.nav-item-expanded > .nav-link i {
            color: #ffffff !important;
        }

        .sidebar .nav-link.nav-parent-active::after,
        .sidebar .nav-item-submenu.nav-item-open > .nav-link::after,
        .sidebar .nav-item-submenu.nav-item-expanded > .nav-link::after {
            color: #ffffff !important;
            opacity: 1 !important;
        }

        .sidebar .nav-item-submenu.nav-item-open .nav-group-sub,
        .sidebar .nav-item-submenu.nav-item-expanded .nav-group-sub,
        .sidebar .nav-group-sub.show {
            background-color: rgba(0, 0, 0, 0.15) !important;
            border-radius: 0 0 8px 8px !important;
            padding: 4px 0 8px 0 !important;
        }

        .sidebar .nav-group-sub .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.6) !important;
            background-color: transparent !important;
            font-weight: 400 !important;
            padding-left: 2.8rem !important;
            border-left: 3px solid transparent !important;
        }

        .sidebar .nav-group-sub .nav-item .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.06) !important;
        }

        .sidebar .nav-group-sub .nav-item .nav-link.nav-child-active,
        .sidebar .nav-group-sub li .nav-link.nav-child-active,
        .sidebar ul.nav-group-sub li a.nav-link.nav-child-active,
        .sidebar-main .nav-group-sub .nav-item > a.nav-child-active {
            color: #ffffff !important;
            font-weight: 700 !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-left: 3px solid #ffffff !important;
            border-radius: 0 !important;
            margin: 0 !important;
            opacity: 1 !important;
        }

        .sidebar .nav-item:not(.nav-item-submenu) > .nav-link.active {
            background-color: rgba(0, 0, 0, 0.2) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            margin: 0 4px !important;
            border-left: none !important;
        }

        /* ================= DOSEN ================= */
        body.is-dosen .sidebar-main {
            display: none !important;
        }

        body.is-dosen .content-wrapper {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }

        body.is-dosen .page-content {
            padding-left: 0 !important;
        }

        /* ================= OPERATOR - LAYOUT ================= */
        .page-content {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        .content-wrapper {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        .content-inner {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        .content {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        /* Desktop: operator content geser ke kanan sidebar */
       body:not(.is-dosen) .content-wrapper {
    flex: 1 1 auto !important;
    min-width: 0 !important;
}

        /* Mobile: sidebar hidden, content full width */
        @media (max-width: 991px) {
            .sidebar-main {
                position: fixed !important;
                top: 0 !important;
                left: -260px !important;
                width: 260px !important;
                height: 100% !important;
                z-index: 9999 !important;
                transition: left 0.3s ease !important;
            }
            .sidebar-main.mobile-open {
                left: 0 !important;
            }
            body:not(.is-dosen) .content-wrapper {
                margin-left: 0 !important;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 9998;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }

        /* ================= CONTENT AREA ================= */
        .content-wrapper,
        .content-inner,
        .content,
        body {
            background-color: #ffffff !important;
            color: #333333 !important;
        }

        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #eeeeee !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02) !important;
            z-index: 2000 !important;
            position: relative;
        }

        .dropdown-menu {
            z-index: 3000 !important;
        }

        .navbar-nav-link {
            color: #333 !important;
        }

        /* ================= TOMBOL AKSI TABEL ================= */
        .content .btn-icon {
            width: 34px !important;
            height: 34px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            padding: 0 !important;
        }

        .content .btn-outline-info {
            border: 1px solid #0dcaf0 !important;
            color: #0dcaf0 !important;
            background: #ffffff !important;
        }
        .content .btn-outline-info:hover {
            background: #0dcaf0 !important;
            color: #ffffff !important;
        }

        .content .btn-outline-primary {
            border: 1px solid #0070C0 !important;
            color: #0070C0 !important;
            background: #ffffff !important;
        }
        .content .btn-outline-primary:hover {
            background: #0070C0 !important;
            color: #ffffff !important;
        }

        .content .btn-outline-danger {
            border: 1px solid #dc3545 !important;
            color: #dc3545 !important;
            background: #ffffff !important;
        }
        .content .btn-outline-danger:hover {
            background: #dc3545 !important;
            color: #ffffff !important;
        }

        .content .btn i {
            font-size: 16px !important;
            color: inherit !important;
            line-height: 1 !important;
        }

        /* ================= TABLE ================= */
        .content table,
        .content table td,
        .content table th,
        .content .table td,
        .content .table th {
            color: #333333 !important;
            background-color: #ffffff !important;
        }

        .content table thead th {
            color: #555555 !important;
            background-color: #f8f9fa !important;
            font-weight: 600 !important;
            border-bottom: 2px solid #dee2e6 !important;
        }

        .content table tbody tr:hover td {
            background-color: #f1fdf9 !important;
            color: #333333 !important;
        }

        /* ================= CARD ================= */
        .content .card,
        .content .card-body,
        .content .card-header,
        .content .card-footer {
            background-color: #ffffff !important;
            color: #333333 !important;
            border-color: #e9ecef !important;
        }

        .content .card-title,
        .content .card-header h1,
        .content .card-header h2,
        .content .card-header h3,
        .content .card-header h4,
        .content .card-header h5,
        .content .card-header h6 {
            color: #222222 !important;
        }

        /* ================= FORM ================= */
        .content .form-control,
        .content .form-select {
            color: #333333 !important;
            background-color: #ffffff !important;
            border-color: #ced4da !important;
        }

        .content .form-label,
        .content label {
            color: #444444 !important;
            font-weight: 500 !important;
        }

        .content .form-control:focus,
        .content .form-select:focus {
            border-color: #0070C0 !important;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25) !important;
        }

        /* ================= TEXT ================= */
        .content h1, .content h2, .content h3,
        .content h4, .content h5, .content h6 {
            color: #222222 !important;
        }

        .content p,
        .content span,
        .content div,
        .content li,
        .content a:not(.btn) {
            color: #333333 !important;
        }

        .content .badge {
            color: #ffffff !important;
        }

        /* ================= MODAL ================= */
        .modal-content {
            background-color: #ffffff !important;
            color: #333333 !important;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            background-color: #ffffff !important;
            color: #333333 !important;
        }

        .modal-title {
            color: #222222 !important;
            font-weight: 600 !important;
        }

        .modal .form-label,
        .modal label {
            color: #444444 !important;
        }

        .modal .form-control,
        .modal .form-select {
            color: #333333 !important;
            background-color: #ffffff !important;
        }

        /* ================= BREADCRUMB ================= */
        .breadcrumb-item,
        .breadcrumb-item a,
        .breadcrumb-item.active {
            color: #555555 !important;
        }

        /* ================= DROPDOWN ================= */
        .content .dropdown-menu {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }

        .content .dropdown-item {
            color: #333333 !important;
        }

        .content .dropdown-item:hover {
            background-color: #f1fdf9 !important;
            color: #0070C0 !important;
        }

        /* ================= DOSEN HERO & CARDS ================= */
        .bima-hero,
        .bima-hero h2, .bima-hero h4, .bima-hero p,
        .bima-hero div, .bima-hero span, .bima-hero strong,
        .bima-hero .period-info, .bima-hero .hero-date-box {
            color: #ffffff !important;
        }

        .card-stat-bima,
        .card-stat-bima .stat-number,
        .card-stat-bima .stat-text,
        .card-stat-bima .stat-text .title,
        .card-stat-bima .stat-text .sub,
        .card-stat-bima span,
        .card-stat-bima div {
            color: #ffffff !important;
        }

        .bima-card-header,
        .bima-card-header span,
        .bima-card-header a {
            color: #ffffff !important;
        }

        .profile-info h6 { color: #0c2461 !important; }
        .profile-info p  { color: #6c757d !important; }
        .stat-item .label { color: #adb5bd !important; }
        .stat-item .value { color: #0c2461 !important; }
        .list-usulan li   { color: #6c757d !important; }
        .more-link        { color: #0c2461 !important; }
        .fw-bold.fs-sm    { color: #212529 !important; }
        .tag-aktif        { color: #0070C0 !important; }
        body:not(.is-dosen) .content-wrapper {
    flex: 1 1 auto !important;
    min-width: 0 !important;
}
    </style>
</head>

<body class="{{ $isDosen ? 'is-dosen' : '' }}">
    <div class="page-content">

        {{-- Sidebar hanya tampil untuk NON-dosen --}}
        @if (!$isDosen)
            @include('layouts.sidebar')
        @endif

        <div class="content-wrapper">

            @include('layouts.navbar')
            @include('layouts.notifications')

            <div class="content-inner">
                <div class="content">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>

                @include('layouts.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showModal', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const el = document.querySelector(data.id);
                if (el) {
                    const modal = new bootstrap.Modal(el);
                    modal.show();
                }
            });

            Livewire.on('hideModal', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const el = document.querySelector(data.id);
                if (el) {
                    const modal = bootstrap.Modal.getInstance(el);
                    if (modal) modal.hide();
                }
            });
        });
    </script>

    <script>
        function initDropdowns() {
            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
                if (!el._dropdown) {
                    el._dropdown = new bootstrap.Dropdown(el);
                }
            });
        }
        document.addEventListener('DOMContentLoaded', initDropdowns);
        document.addEventListener('livewire:navigated', initDropdowns);
        document.addEventListener('livewire:update', initDropdowns);
    </script>
    <!-- Overlay untuk mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar-main');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('mobileSidebarToggle');

    // Buka sidebar mobile
    if (hamburger) {
        hamburger.addEventListener('click', function () {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show');
        });
    }

    // Tutup sidebar saat klik overlay
    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        });
    }
});
</script>


    @stack('scripts')
    @stack('footer_scripts')

    <!-- Livewire -->
    @livewireScripts
</body>



