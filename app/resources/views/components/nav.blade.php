<nav class="sidebar-nav scroll-sidebar" style="height: calc(100vh - 76px);" data-simplebar>
    <ul id="sidebarnav">
        <!-- ---------------------------------- -->
        <!-- Umum -->
        <!-- ---------------------------------- -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Umum</span>
        </li>

        {{-- Beranda --}}
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}"
                aria-expanded="false">
                <span>
                    <i class="ti ti-home"></i>
                </span>
                <span class="hide-menu">Beranda</span>
            </a>
        </li>

        @if (in_array('Admin', $auth->roles) || in_array('Admin', $auth->akses))
            {{-- Hak Akses --}}
            <li class="sidebar-item">
                <a class="sidebar-link {{ request()->routeIs('app.hak-akses') ? 'active' : '' }}"
                    href="{{ route('app.hak-akses') }}" aria-expanded="false">
                    <span>
                        <i class="ti ti-lock"></i>
                    </span>
                    <span class="hide-menu">Hak Akses</span>
                </a>
            </li>
        @endif

        <!-- ---------------------------------- -->
        <!-- Legal -->
        <!-- ---------------------------------- -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Legal</span>
        </li>

        {{-- Terms --}}
        <li class="sidebar-item">
            <a class="sidebar-link" target="_blank" href="{{ route('legal.terms') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-notes"></i>
                </span>
                <span class="hide-menu">Terms</span>
            </a>
        </li>

        {{-- Privacy --}}
        <li class="sidebar-item">
            <a class="sidebar-link" target="_blank" href="{{ route('legal.privacy') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-notes"></i>
                </span>
                <span class="hide-menu">Privacy</span>
            </a>
        </li>
    </ul>
</nav>



