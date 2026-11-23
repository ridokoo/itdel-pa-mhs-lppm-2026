@extends('layouts.guest_master')

@section('content')
<!-- 1. Penyatuan Font dan Konfigurasi Tailwind agar sama dengan Galeri -->
<head>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    "colors": {
                        "primary": "#002155",
                        "tertiary-fixed": "#f2daff",
                        "background": "#fbf9f8",
                        "on-background": "#1b1c1c",
                    },
                    "spacing": {
                        "container-max": "1320px",
                        "gutter": "1.5rem",
                        "section-padding-desktop": "5rem",
                        "section-padding-mobile": "2.5rem",
                    },
                    "fontFamily": {
                        "display-lg": ["Poppins"],
                        "body-lg": ["Poppins"],
                        "label-sm": ["Poppins"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .announcement-body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<div class="announcement-body bg-background text-on-background">
    <!-- Breadcrumb & Header Pengumuman -->
    <section class="relative py-24 overflow-hidden bg-primary">
        <!-- Latar Belakang Gambar dengan Overlay Biru (Opacity 20) -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <img
                alt="background"
                class="w-full h-full object-cover"
                src="{{ asset('assets/img/pengumuman.jpeg') }}"
            />
        </div>

        <!-- Ikon Dekorasi Besar di Belakang (Megaphone/Campaign) -->
        <div class="absolute -right-20 -top-20 text-tertiary-fixed/10 select-none">
            <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">campaign</span>
        </div>

        <!-- Konten Header -->
        <div class="relative px-gutter max-w-container-max mx-auto text-center md:text-left">
            <!-- Breadcrumb Navigasi -->
            <nav class="flex items-center gap-2 text-white/80 font-label-sm mb-4">
                <a class="hover:text-white transition-colors" href="{{ route('welcome.guest') }}">Beranda</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-tertiary-fixed font-bold">Announcements</span>
            </nav>

            <!-- Judul Utama (Ukuran Font Disamakan: text-display-lg) -->
        <h1 class="text-white font-display-lg text-4xl md:text-5xl lg:text-[48px] font-bold leading-tight tracking-tight">
            Pengumuman Resmi
        </h1>

            <!-- Deskripsi (Ukuran Font Disamakan: text-body-lg) -->
            <p class="text-white/70 max-w-2xl mt-4 font-body-lg text-body-lg">
                Dapatkan informasi resmi, surat edaran, dan pemberitahuan penting terkait administrasi penelitian serta pengabdian masyarakat di Institut Teknologi Del.
            </p>
        </div>
    </section>

    <!-- Filter/Search Bar -->
    <section class="max-w-[1320px] mx-auto px-4 md:px-20 mt-12 mb-8">
        <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <button class="p-2 bg-itdel-blue text-white rounded-lg flex items-center justify-center transition-all hover:bg-primary">
                    <span class="material-symbols-outlined">grid_view</span>
                </button>
                <button class="p-2 bg-white text-on-surface-variant border border-outline-variant rounded-lg flex items-center justify-center transition-all hover:bg-surface-container-high">
                    <span class="material-symbols-outlined">format_list_bulleted</span>
                </button>
            </div>
            <div class="flex flex-1 w-full md:max-w-md relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input type="text" placeholder="Search By Keywords"
                       class="w-full pl-10 pr-4 py-2 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-itdel-blue focus:border-itdel-blue outline-none text-base"/>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="hidden lg:block text-sm font-semibold text-on-surface-variant">Sort By:</label>
                <select class="w-full md:w-48 px-4 py-2 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-itdel-blue outline-none text-sm font-semibold">
                    <option>Terbaru</option>
                    <option>Terlama</option>
                    <option>A-Z</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Announcement Grid -->
    <section class="max-w-[1320px] mx-auto px-4 md:px-20 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($announcements as $item)
            <div class="group bg-white rounded-xl border border-outline-variant overflow-hidden flex flex-col shadow-sm transition-all hover:shadow-md hover:-translate-y-1">
                <div class="p-6 flex-grow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="material-symbols-outlined text-itdel-blue text-[32px]">newspaper</span>
                        <span class="bg-surface-container-high text-on-surface-variant px-3 py-1 rounded-full text-xs font-medium">
{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-primary group-hover:text-itdel-blue transition-colors mb-3">
                        <a href="{{ route('guest.announcement.detail', $item->id) }}">{{ $item->title }}</a>
                    </h3>
                    <p class="text-base text-on-surface-variant line-clamp-2">
                        {{ $item->content }}
                    </p>
                </div>
                <a href="{{ route('guest.announcement.detail', $item->id) }}" class="block h-[250px] overflow-hidden">
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <img src="{{ asset('assets/img/service/service-4.jpg') }}" alt="Default Image"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @endif
                </a>
            </div>
            @empty
            <div class="col-span-3 text-center py-16">
                <span class="material-symbols-outlined text-outline text-[64px] mb-4 block">campaign</span>
                <p class="text-lg text-on-surface-variant">Belum ada pengumuman yang diterbitkan saat ini.</p>
            </div>
            @endforelse

        </div>
    </section>

    <!-- Pagination -->
    @if($announcements->hasPages())
    <section class="max-w-[1320px] mx-auto px-4 md:px-20 mb-24">
        <div class="flex items-center justify-center gap-2">

            @if($announcements->onFirstPage())
                <button disabled class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded text-outline opacity-40 cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
            @else
                <a href="{{ $announcements->previousPageUrl() }}"
                   class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded hover:bg-surface-container-high transition-colors text-outline">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
            @endif

            @foreach($announcements->getUrlRange(1, $announcements->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                   class="w-10 h-10 flex items-center justify-center rounded text-sm font-semibold transition-colors
                          {{ $page == $announcements->currentPage()
                              ? 'bg-itdel-blue text-white'
                              : 'border border-outline-variant hover:bg-surface-container-high text-on-background' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if($announcements->hasMorePages())
                <a href="{{ $announcements->nextPageUrl() }}"
                   class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded hover:bg-surface-container-high transition-colors text-outline">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            @else
                <button disabled class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded text-outline opacity-40 cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            @endif

        </div>
    </section>
    @endif

</main>
@endsection



