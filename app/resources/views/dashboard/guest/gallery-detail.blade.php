@extends('layouts.guest_master')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $gallery->title }} - LPPM IT Del</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
{{-- FsLightbox untuk preview foto --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.umd.js"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "on-secondary-fixed": "#001b3c",
                    "secondary-fixed-dim": "#a8c8ff",
                    "inverse-primary": "#b0c6ff",
                    "on-background": "#1b1c1c",
                    "tertiary-fixed": "#f2daff",
                    "secondary-fixed": "#d6e3ff",
                    "surface": "#fbf9f8",
                    "inverse-surface": "#303030",
                    "primary-fixed": "#d9e2ff",
                    "primary-container": "#003580",
                    "secondary-container": "#72a9ff",
                    "primary": "#002155",
                    "surface-container-highest": "#e4e2e2",
                    "surface-container-low": "#f5f3f3",
                    "surface-dim": "#dbd9d9",
                    "surface-container-high": "#eae8e7",
                    "error": "#ba1a1a",
                    "on-secondary-fixed-variant": "#00468a",
                    "on-primary-fixed": "#001945",
                    "error-container": "#ffdad6",
                    "surface-container": "#f0eded",
                    "outline": "#747782",
                    "surface-tint": "#375ca8",
                    "on-secondary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "on-error": "#ffffff",
                    "inverse-on-surface": "#f2f0f0",
                    "on-tertiary-fixed-variant": "#5e3085",
                    "on-primary-fixed-variant": "#1a438e",
                    "secondary": "#175eae",
                    "surface-bright": "#fbf9f8",
                    "on-surface-variant": "#434651",
                    "primary-fixed-dim": "#b0c6ff",
                    "background": "#fbf9f8",
                    "on-primary-container": "#7fa1f2",
                    "on-surface": "#1b1c1c",
                    "on-error-container": "#93000a",
                    "outline-variant": "#c4c6d3",
                    "tertiary-fixed-dim": "#e0b7ff",
                    "on-tertiary-fixed": "#2d004f",
                    "on-primary": "#ffffff",
                    "on-tertiary": "#ffffff",
                    "tertiary-container": "#502176",
                    "on-tertiary-container": "#c08ee9",
                    "tertiary": "#39025f",
                    "surface-variant": "#e4e2e2",
                    "on-secondary-container": "#003d79",
                    // "racing-green": "#158771"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                "spacing": {
                    "container-max": "1320px",
                    "gutter": "1.5rem",
                    "base-unit": "8px",
                    "section-padding-mobile": "2.5rem",
                    "section-padding-desktop": "5rem"
                },
                "fontFamily": {
                    "display-lg": ["Poppins"],
                    "body-lg": ["Poppins"],
                    "headline-md": ["Poppins"],
                    "headline-lg": ["Poppins"],
                    "label-sm": ["Poppins"],
                    "label-md": ["Poppins"],
                    "body-md": ["Poppins"]
                },
                "fontSize": {
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-md": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "label-md": ["14px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}]
                }
            }
        }
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
</style>
</head>
<body class="bg-background text-on-background font-body-md">

<!-- Breadcrumb Header -->
<header class="relative h-64 flex items-center overflow-hidden">
    <img
        class="absolute inset-0 w-full h-full object-cover"
        src="{{ asset('storage/' . $gallery->cover_image) }}"
        alt="{{ $gallery->title }}"
    />
    <div class="absolute inset-0 bg-racing-green/80"></div>
    <div class="absolute -right-20 -bottom-20 opacity-10 rotate-12">
        <svg fill="white" height="400" width="400" viewBox="0 0 100 100">
            <path d="M50 0C50 0 30 40 0 50C30 60 50 100 50 100C50 100 70 60 100 50C70 40 50 0 50 0Z"></path>
        </svg>
    </div>
    <div class="relative w-full px-gutter max-w-container-max mx-auto text-white">
        <nav class="flex items-center gap-2 text-label-sm font-label-sm mb-4">
            <a class="hover:underline opacity-80" href="{{ route('welcome.guest') }}">Beranda</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:underline opacity-80" href="{{ route('guest.gallery.index') }}">Album</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="font-bold">Detail Galeri</span>
        </nav>
        <h1 class="font-headline-lg text-headline-lg font-bold">Detail Galeri</h1>
    </div>
</header>

<!-- Main Content -->
<main class="py-section-padding-desktop px-gutter max-w-container-max mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Konten Utama -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Cover Hero -->
            <div class="rounded-xl overflow-hidden shadow-lg border border-outline-variant relative group">
                <img
                    class="w-full aspect-video object-cover transition-transform duration-700 group-hover:scale-105"
                    src="{{ asset('storage/' . $gallery->cover_image) }}"
                    alt="{{ $gallery->title }}"
                />
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center pointer-events-none">
                    <span class="material-symbols-outlined text-white text-6xl opacity-0 group-hover:opacity-100 transition-opacity">zoom_in</span>
                </div>
            </div>

            <!-- Info Album + Deskripsi -->
            <div class="grid md:grid-cols-2 gap-8">

                <!-- Info Box -->
                <div class="bg-surface-container-low p-6 rounded-xl border border-outline-variant">
                    <h2 class="font-headline-md text-headline-md text-primary mb-4">Informasi Album</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary">label</span>
                            <div>
                                <p class="font-label-sm text-on-surface-variant">Judul Kegiatan</p>
                                <p class="font-body-md font-semibold">{{ $gallery->title }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary">calendar_today</span>
                            <div>
                                <p class="font-label-sm text-on-surface-variant">Tanggal Kegiatan</p>
                                <p class="font-body-md font-semibold">
                                    {{ \Carbon\Carbon::parse($gallery->event_date)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary">location_on</span>
                            <div>
                                <p class="font-label-sm text-on-surface-variant">Lokasi</p>
                                <p class="font-body-md font-semibold">Institut Teknologi Del</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary">photo_library</span>
                            <div>
                                <p class="font-label-sm text-on-surface-variant">Jumlah Media</p>
                                <p class="font-body-md font-semibold">{{ $gallery->items->count() }} Foto/Video</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-4">
                    <h2 class="font-headline-md text-headline-md text-primary">Deskripsi Kegiatan</h2>
                    <div class="text-on-surface-variant leading-relaxed">
                        {!! nl2br(e($gallery->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Grid Foto Mosaic (4 foto pertama) -->
            @if($gallery->items->count() > 0)
            <div class="space-y-4">
                <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-racing-green pl-4">
                    Sorotan Media
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Foto 1: Kiri besar (portrait) --}}
                    @if(isset($gallery->items[0]))
                    <a
                        data-fslightbox="gallery"
                        href="{{ asset('storage/' . $gallery->items[0]->file_path) }}"
                        class="relative overflow-hidden rounded-xl border border-outline-variant group cursor-pointer h-[420px]"
                    >
                        <img
                            src="{{ asset('storage/' . $gallery->items[0]->file_path) }}"
                            alt="Foto 1"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-4xl scale-0 group-hover:scale-100 transition-transform">search_check</span>
                        </div>
                    </a>
                    @endif

                    {{-- Foto 2, 3, 4: Kanan stack --}}
                    <div class="grid grid-rows-2 gap-4 h-[420px]">

                        @if(isset($gallery->items[1]))
                        <a
                            data-fslightbox="gallery"
                            href="{{ asset('storage/' . $gallery->items[1]->file_path) }}"
                            class="relative overflow-hidden rounded-xl border border-outline-variant group cursor-pointer"
                        >
                            <img
                                src="{{ asset('storage/' . $gallery->items[1]->file_path) }}"
                                alt="Foto 2"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-3xl scale-0 group-hover:scale-100 transition-transform">search_check</span>
                            </div>
                        </a>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            @if(isset($gallery->items[2]))
                            <a
                                data-fslightbox="gallery"
                                href="{{ asset('storage/' . $gallery->items[2]->file_path) }}"
                                class="relative overflow-hidden rounded-xl border border-outline-variant group cursor-pointer"
                            >
                                <img
                                    src="{{ asset('storage/' . $gallery->items[2]->file_path) }}"
                                    alt="Foto 3"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-2xl scale-0 group-hover:scale-100 transition-transform">search_check</span>
                                </div>
                            </a>
                            @endif

                            @if(isset($gallery->items[3]))
                            <a
                                data-fslightbox="gallery"
                                href="{{ asset('storage/' . $gallery->items[3]->file_path) }}"
                                class="relative overflow-hidden rounded-xl border border-outline-variant group cursor-pointer"
                            >
                                <img
                                    src="{{ asset('storage/' . $gallery->items[3]->file_path) }}"
                                    alt="Foto 4"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-2xl scale-0 group-hover:scale-100 transition-transform">search_check</span>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Foto ke-5 dst: Grid 3 kolom --}}
            @if($gallery->items->count() > 4)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($gallery->items->slice(4) as $extra)
                <a
                    data-fslightbox="gallery"
                    href="{{ asset('storage/' . $extra->file_path) }}"
                    class="relative overflow-hidden rounded-xl border border-outline-variant group cursor-pointer aspect-square"
                >
                    <img
                        src="{{ asset('storage/' . $extra->file_path) }}"
                        alt="{{ $gallery->title }}"
                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500"
                    />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl scale-0 group-hover:scale-100 transition-transform">search_check</span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-8">

            <!-- Album Lainnya -->
            <div class="bg-white rounded-xl border border-outline-variant overflow-hidden shadow-sm">
                <div class="bg-primary-container p-4">
                    <h3 class="text-white font-headline-md text-headline-md flex items-center gap-2">
                        <span class="material-symbols-outlined">collections_bookmark</span>
                        Album Lainnya
                    </h3>
                </div>
                <div class="p-2 divide-y divide-outline-variant">
                    @php
                        $otherAlbums = \App\Models\Gallery::where('status', 'published')
                            ->where('id', '!=', $gallery->id)
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($otherAlbums as $album)
                    <a
                        href="{{ route('guest.gallery.detail', $album->id) }}"
                        class="flex items-center gap-4 p-3 hover:bg-surface-container transition-colors group"
                    >
                        <img
                            src="{{ asset('storage/' . $album->cover_image) }}"
                            alt="{{ $album->title }}"
                            class="w-16 h-16 rounded object-cover shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <p class="font-label-md leading-tight group-hover:text-secondary truncate">
                                {{ Str::limit($album->title, 30) }}
                            </p>
                            <p class="text-label-sm text-on-surface-variant mt-1">
                                {{ \Carbon\Carbon::parse($album->event_date)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <span class="material-symbols-outlined text-secondary-container shrink-0">chevron_right</span>
                    </a>
                    @empty
                    <p class="p-4 text-on-surface-variant text-label-sm">Tidak ada album lain.</p>
                    @endforelse
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="bg-surface-container-low p-6 rounded-xl border border-outline-variant">
                <h3 class="text-primary font-headline-md text-headline-md mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">schedule</span>
                    Jam Operasional
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant">
                        <span class="text-on-surface-variant">Senin - Kamis</span>
                        <span class="font-bold text-on-surface">08:00 - 17:00</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant">
                        <span class="text-on-surface-variant">Jumat</span>
                        <span class="font-bold text-on-surface">08:00 - 16:30</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Sabtu - Minggu</span>
                        <span class="bg-racing-green text-white px-3 py-1 rounded font-label-sm uppercase tracking-wider">Tutup</span>
                    </div>
                </div>
                <p class="mt-4 text-label-sm text-on-surface-variant italic leading-relaxed">
                    *Layanan administrasi dan konsultasi dilakukan pada jam kerja efektif di Gedung Rektorat Lt. 1.
                </p>
            </div>

            <!-- Hubungi Kami -->
            <div class="bg-primary text-on-primary p-6 rounded-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <h3 class="font-headline-md text-headline-md mb-6 relative z-10">Hubungi Kami</h3>
                <div class="space-y-5 relative z-10">
                    <div class="flex gap-4 items-start">
                        <span class="material-symbols-outlined text-secondary-container shrink-0">location_on</span>
                        <p class="text-label-md opacity-90 leading-relaxed">
                            Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba, Sumatera Utara
                        </p>
                    </div>
                    <div class="flex gap-4 items-center">
                        <span class="material-symbols-outlined text-secondary-container shrink-0">call</span>
                        <a href="tel:+62632331234" class="text-label-md opacity-90 hover:opacity-100">+62 632 331234</a>
                    </div>
                    <div class="flex gap-4 items-center">
                        <span class="material-symbols-outlined text-secondary-container shrink-0">mail</span>
                        <a href="mailto:lppm@del.ac.id" class="text-label-md opacity-90 hover:opacity-100">lppm@del.ac.id</a>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</main>

<!-- Footer -->


<script>
    document.querySelectorAll('.group.cursor-pointer').forEach(el => {
        el.addEventListener('mouseenter', () => {
            const icon = el.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.style.transform = 'scale(1.2) rotate(5deg)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
        el.addEventListener('mouseleave', () => {
            const icon = el.querySelector('.material-symbols-outlined');
            if (icon) icon.style.transform = 'scale(1) rotate(0deg)';
        });
    });
</script>

</body>
</html>

@endsection



