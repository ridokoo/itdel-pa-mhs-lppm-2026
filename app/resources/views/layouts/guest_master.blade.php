<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LPPM IT Del')</title>

    {{-- Favicon --}}
<link rel="shortcut icon" href="{{ asset('img/del.png') }}" type="image/png">
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    {{-- Tailwind Config --}}
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "outline": "#747782",
                        "inverse-primary": "#b0c6ff",
                        "surface-bright": "#fbf9f8",
                        "on-surface-variant": "#434651",
                        "primary": "#002155",
                        "secondary-container": "#72a9ff",
                        "surface-container-highest": "#e4e2e2",
                        "surface-dim": "#dbd9d9",
                        "surface": "#fbf9f8",
                        "background": "#fbf9f8",
                        "error": "#ba1a1a",
                        "secondary-fixed": "#d6e3ff",
                        "tertiary": "#39025f",
                        "on-surface": "#1b1c1c",
                        "outline-variant": "#c4c6d3",
                        "surface-container-high": "#eae8e7",
                        "primary-container": "#003580",
                        "primary-fixed": "#d9e2ff",
                        "inverse-surface": "#303030",
                        "surface-container-low": "#f5f3f3",
                        "surface-container-lowest": "#ffffff",
                        "on-error": "#ffffff",
                        "surface-container": "#f0eded",
                        "error-container": "#ffdad6",
                        "surface-variant": "#e4e2e2",
                        "on-secondary": "#ffffff",
                        "on-primary": "#ffffff",
                        "on-background": "#1b1c1c",
                        "institutional-purple": "#46166c",
                        "itdel-blue": "#1a81c2",
                        "itdel-deep-blue": "#003580",
                        "vista-white": "#f2f2f2",
                        "racing-green": "#003580"
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    fontFamily: {
                        sans: ["Poppins", "sans-serif"]
                    },
                    maxWidth: {
                        "container-max": "1320px"
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .footer-link-hover:hover { color: #ffffff; text-decoration: underline; }

        /* Preloader */
        #preloader {
            position: fixed; inset: 0; z-index: 9999;
            background: #003580;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.5s ease;
        }
        #preloader.hidden { opacity: 0; pointer-events: none; }

        /* Active nav indicator */
        .nav-link-active {
            border-bottom: 2px solid #d6e3ff;
        }

        /* Dark mode toggle */
        .dark .dark-invert { filter: invert(1); }
    </style>

    @livewireStyles
    @stack('styles')
</head>

<body class="bg-surface font-sans text-on-surface">

    {{-- ===================== PRELOADER ===================== --}}
    <div id="preloader">
        <div class="text-center">
            <div class="w-12 h-12 border-4 border-white/30 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
            <span class="text-white text-sm font-medium tracking-widest uppercase">LPPM IT Del</span>
        </div>
    </div>

    {{-- ===================== HEADER / NAVBAR ===================== --}}
    <header class="sticky top-0 z-50">

        {{-- Layer 1: Institutional Bar --}}
        <div class="bg-institutional-purple text-white py-2 px-4 md:px-20">
            <div class="max-w-container-max mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-semibold tracking-wide">Institut Teknologi Del</span>
                </div>
                {{-- Login Button --}}
                <a href="{{ route('auth.login') }}"
                   class="bg-white/10 hover:bg-white/20 transition-all px-4 py-1 rounded text-xs font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">login</span>
                    Login
                </a>
            </div>
        </div>

        {{-- Layer 2: Primary Navigation --}}
        <nav class="bg-itdel-blue text-white shadow-sm">
            <div class="max-w-container-max mx-auto px-4 md:px-20 flex items-center justify-between h-16">

                {{-- Brand Logo --}}
<a href="{{ route('welcome.guest') }}" class="flex items-center gap-2 font-headline-md text-headline-md font-bold text-white hover:opacity-80 transition-opacity">
    <img src="{{ asset('img/del.png') }}" alt="Logo IT Del" class="h-8 w-auto">
    LPPM IT Del
</a>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center space-x-6">

                    {{-- Beranda --}}
                    <a href="{{ route('welcome.guest') }}"
                       class="text-sm font-semibold pb-1 transition-all hover:opacity-100
                              {{ request()->routeIs('welcome.guest') ? 'border-b-2 border-secondary-fixed opacity-100' : 'opacity-80' }}">
                        Beranda
                    </a>

                    {{-- Tentang Kami (Dropdown) --}}
                    <div class="relative group">
                        <button class="flex items-center space-x-1 text-sm font-semibold pb-1 transition-all
                                       {{ request()->routeIs('guest.profile') || request()->routeIs('guest.profile.detail') || request()->routeIs('guest.gallery.*') ? 'border-b-2 border-secondary-fixed opacity-100' : 'opacity-80 hover:opacity-100' }}">
                            <span>Tentang Kami</span>
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </button>
                        <div class="absolute top-full left-0 w-48 bg-white text-primary shadow-xl rounded-lg hidden group-hover:block border border-outline-variant py-2" style="margin-top: -1px; padding-top: 8px;">                            <a href="{{ route('guest.profile') }}"
                               class="block px-4 py-2 hover:bg-surface-container text-sm font-semibold
                                      {{ request()->routeIs('guest.profile') || request()->routeIs('guest.profile.detail') ? 'text-itdel-blue' : '' }}">
                                Profil LPPM
                            </a>
                            <a href="{{ route('guest.gallery.index') }}"
                               class="block px-4 py-2 hover:bg-surface-container text-sm font-semibold
                                      {{ request()->routeIs('guest.gallery.*') ? 'text-itdel-blue' : '' }}">
                                Galeri
                            </a>
                        </div>
                    </div>

                    {{-- Berita --}}
                    <a href="{{ route('guest.news.index') }}"
                       class="text-sm font-semibold pb-1 transition-all hover:opacity-100
                              {{ request()->routeIs('guest.news.*') ? 'border-b-2 border-secondary-fixed opacity-100' : 'opacity-80' }}">
                        Berita
                    </a>

                    {{-- Pengumuman --}}
                    <a href="{{ route('guest.announcement.index') }}"
                       class="text-sm font-semibold pb-1 transition-all hover:opacity-100
                              {{ request()->routeIs('guest.announcement.*') ? 'border-b-2 border-secondary-fixed opacity-100' : 'opacity-80' }}">
                        Pengumuman
                    </a>
                    {{-- Rekapitulasi Data --}}
                    <a href="{{ route('guest.rekapitulasi.index') }}"
                    class="text-sm font-semibold pb-1 transition-all hover:opacity-100
                            {{ request()->routeIs('guest.rekapitulasi.*') ? 'border-b-2 border-secondary-fixed opacity-100' : 'opacity-80' }}">
                        Rekapitulasi Data
                    </a>

                    {{-- Dokumen (Dropdown Dinamis dari DB) --}}
                    <div class="relative group">
                        <button class="flex items-center space-x-1 text-sm font-semibold pb-1 transition-all
                                       {{ request()->routeIs('guest.documents.*') ? 'border-b-2 border-secondary-fixed opacity-100' : 'opacity-80 hover:opacity-100' }}">
                            <span>Dokumen</span>
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </button>
<div class="absolute top-full right-0 w-56 bg-white text-primary shadow-xl rounded-lg hidden group-hover:block border border-outline-variant py-2" style="margin-top: -1px; padding-top: 8px;">                            @php
                                $navbarCategories = \App\Models\DocumentCategory::where('is_active', true)->orderBy('name', 'asc')->get();
                                $currentCatId = request()->route('id');
                            @endphp
                            @forelse($navbarCategories as $cat)
                                <a href="{{ route('guest.documents.category', $cat->id) }}"
                                   class="block px-4 py-2 hover:bg-surface-container text-sm font-semibold
                                          {{ (request()->routeIs('guest.documents.category') && $currentCatId == $cat->id) ? 'text-itdel-blue' : '' }}">
                                    {{ $cat->name }}
                                </a>
                            @empty
                                <span class="block px-4 py-2 text-sm text-outline">Belum ada kategori</span>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Mobile Hamburger --}}
                <button class="md:hidden text-white" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div class="hidden md:hidden bg-itdel-blue pb-4 px-4 space-y-2" id="mobile-menu">
                <a href="{{ route('welcome.guest') }}"
                   class="block py-2 border-b border-white/10 text-sm font-semibold {{ request()->routeIs('welcome.guest') ? 'text-secondary-fixed' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('guest.profile') }}"
                   class="block py-2 border-b border-white/10 text-sm font-semibold {{ request()->routeIs('guest.profile') ? 'text-secondary-fixed' : '' }}">
                    Tentang Kami
                </a>
                <a href="{{ route('guest.gallery.index') }}"
                   class="block py-2 border-b border-white/10 text-sm font-semibold {{ request()->routeIs('guest.gallery.*') ? 'text-secondary-fixed' : '' }}">
                    Galeri
                </a>
                <a href="{{ route('guest.news.index') }}"
                   class="block py-2 border-b border-white/10 text-sm font-semibold {{ request()->routeIs('guest.news.*') ? 'text-secondary-fixed' : '' }}">
                    Berita
                </a>
                <a href="{{ route('guest.announcement.index') }}"
                   class="block py-2 border-b border-white/10 text-sm font-semibold {{ request()->routeIs('guest.announcement.*') ? 'text-secondary-fixed' : '' }}">
                    Pengumuman
                </a>
                <a href="{{ route('guest.rekapitulasi.index') }}"
                class="block py-2 border-b border-white/10 text-sm font-semibold {{ request()->routeIs('guest.rekapitulasi.*') ? 'text-secondary-fixed' : '' }}">
                    Rekapitulasi Data
                </a>
                <a href="{{ route('guest.documents.index') }}"
                   class="block py-2 text-sm font-semibold {{ request()->routeIs('guest.documents.*') ? 'text-secondary-fixed' : '' }}">
                    Dokumen
                </a>
            </div>
        </nav>
    </header>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===================== NEWSLETTER SECTION ===================== --}}
    <section class="py-16 bg-vista-white">
        <div class="max-w-container-max mx-auto px-4 md:px-20">
            <div class="bg-white p-8 md:p-12 rounded-2xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 border border-outline-variant/30">
                <div class="md:w-3/5">
                    <h2 class="text-primary text-2xl font-bold mb-4">Langganan Informasi Terbaru</h2>
                    <p class="text-on-surface-variant text-sm leading-relaxed">
                        Daftarkan email Anda untuk mendapatkan pemberitahuan mengenai pengumuman resmi, berita penelitian, dan jadwal pengabdian masyarakat terbaru di Institut Teknologi Del.
                    </p>
                </div>
                <div class="md:w-2/5 w-full">
                    <div class="flex space-x-2">
                        <input
                            type="email"
                            placeholder="Alamat Email Anda (Fitur Segera Hadir)"
                            readonly
                            class="w-full border border-outline-variant bg-surface-container-low rounded-lg p-4 text-sm text-on-surface-variant focus:outline-none cursor-not-allowed"
                            style="background: #f9f9f9;"
                        >
                        <button
                            type="button"
                            class="bg-itdel-blue text-white px-5 py-4 rounded-lg font-bold text-sm flex items-center gap-2 whitespace-nowrap opacity-80 cursor-default"
                        >
                            <span>Kirim</span>
                            <span class="material-symbols-outlined text-[18px]">send</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-outline mt-3 ml-1 italic">*Fitur berlangganan akan segera tersedia</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    @php
        $wa   = \App\Models\Contact::where('name', 'like', '%whatsapp%')->orWhereLike('name', '%telepon%')->first();
        $mail = \App\Models\Contact::where('name', 'like', '%email%')->first();
        $loc  = \App\Models\Contact::where('name', 'like', '%lokasi%')
                    ->orWhereLike('name', '%location%')
                    ->orWhereLike('name', '%alamat%')
                    ->first();
    @endphp

    <footer class="bg-racing-green text-white">
        <div class="max-w-container-max mx-auto px-4 md:px-20 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">

                {{-- Kolom 1: Contact Details (Dinamis dari DB) --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-bold mb-4">LPPM IT Del</h4>
                    <p class="text-sm opacity-70 leading-relaxed">
                        Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Institut Teknologi Del.
                    </p>
                    <div class="space-y-4">
                        {{-- Lokasi --}}
                        <div class="flex items-start space-x-3">
                            <span class="material-symbols-outlined text-secondary-container flex-shrink-0">location_on</span>
                            <p class="text-sm opacity-80 leading-relaxed">
                                {{ $loc->contact_value ?? 'Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba, Sumatera Utara' }}
                            </p>
                        </div>
                        {{-- Telepon / WhatsApp --}}
                        <div class="flex items-center space-x-3">
                            <span class="material-symbols-outlined text-secondary-container flex-shrink-0">call</span>
                            <a href="tel:{{ $wa->contact_value ?? '#' }}" class="text-sm opacity-80 hover:opacity-100 transition-opacity">
                                {{ $wa->contact_value ?? 'Belum diatur' }}
                            </a>
                        </div>
                        {{-- Email --}}
                        <div class="flex items-center space-x-3">
                            <span class="material-symbols-outlined text-secondary-container flex-shrink-0">mail</span>
                            <a href="mailto:{{ $mail->contact_value ?? '#' }}" class="text-sm opacity-80 hover:opacity-100 transition-opacity">
                                {{ $mail->contact_value ?? 'Belum diatur' }}
                            </a>
                        </div>
                    </div>
                    {{-- Social Media --}}
                    <div class="flex space-x-3 pt-2">
                        <a href="#" target="_blank" class="bg-white/10 p-2 rounded-full hover:bg-white/20 transition-all">
                            <span class="material-symbols-outlined text-[20px]">public</span>
                        </a>
                        <a href="#" target="_blank" class="bg-white/10 p-2 rounded-full hover:bg-white/20 transition-all">
                            <span class="material-symbols-outlined text-[20px]">alternate_email</span>
                        </a>
                    </div>
                </div>

                {{-- Kolom 2: Lembaga --}}
                <div>
                    <h5 class="text-sm font-bold uppercase tracking-wider mb-6 pb-2 border-b border-white/10 inline-block">Lembaga</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('guest.profile') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Profil LPPM</a></li>
                        <li><a href="{{ route('guest.gallery.index') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Galeri Kegiatan</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Visi &amp; Misi</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Struktur Organisasi</a></li>
                        <li><a href="{{ route('legal.privacy') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('legal.terms') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Syarat &amp; Ketentuan</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Informasi --}}
                <div>
                    <h5 class="text-sm font-bold uppercase tracking-wider mb-6 pb-2 border-b border-white/10 inline-block">Informasi</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('guest.news.index') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Berita Terbaru</a></li>
                        <li><a href="{{ route('guest.announcement.index') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Pengumuman Resmi</a></li>
                        <li><a href="{{ route('guest.documents.index') }}" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Dokumen &amp; Panduan</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Agenda Kegiatan</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Publikasi Riset</a></li>
                    </ul>
                </div>

                {{-- Kolom 4: Tautan Terkait --}}
                <div>
                    <h5 class="text-sm font-bold uppercase tracking-wider mb-6 pb-2 border-b border-white/10 inline-block">Tautan Terkait</h5>
                    <ul class="space-y-3">
                        <li><a href="https://del.ac.id" target="_blank" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Institut Teknologi Del</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Sistem Informasi Hibah</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Portal Jurnal Del</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Repository Dokumen</a></li>
                        <li><a href="#" class="text-sm opacity-70 hover:opacity-100 footer-link-hover transition-colors">Perpustakaan Digital</a></li>
                    </ul>
                </div>
            </div>

            {{-- Footer Bottom --}}
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs opacity-60 text-center md:text-left">
                    &copy; {{ date('Y') }} Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Institut Teknologi Del. Hak Cipta Dilindungi.
                </p>
                <div class="flex space-x-6 text-xs opacity-60">
                    <a href="{{ route('legal.privacy') }}" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="{{ route('legal.terms') }}" class="hover:text-white transition-colors">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ===================== BACK TO TOP ===================== --}}
    <button
        id="back-to-top"
        onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-5 right-5 z-50 bg-itdel-blue text-white w-10 h-10 rounded-full shadow-lg hidden items-center justify-center hover:bg-primary-container transition-all"
        title="Kembali ke atas"
    >
        <span class="material-symbols-outlined text-[20px]">arrow_upward</span>
    </button>

    {{-- ===================== SCRIPTS ===================== --}}
    @livewireScripts

    <script>
        // ---- Preloader ----
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            preloader.classList.add('hidden');
            setTimeout(() => preloader.remove(), 600);
        });

        // ---- Back to Top ----
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.remove('hidden');
                backToTop.classList.add('flex');
            } else {
                backToTop.classList.add('hidden');
                backToTop.classList.remove('flex');
            }
        });

        // ---- Dark Mode Toggle ----
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                icon.innerText = 'light_mode';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                icon.innerText = 'dark_mode';
                localStorage.setItem('theme', 'dark');
            }
        }
        // Persist theme on load
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            document.getElementById('theme-icon').innerText = 'dark_mode';
        }

        // ---- Smooth scroll for anchor links ----
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    window.scrollTo({ top: target.offsetTop - 80, behavior: 'smooth' });
                    document.getElementById('mobile-menu')?.classList.add('hidden');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>



