@extends('layouts.guest_master')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Galeri Album - LPPM IT Del</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "tertiary": "#39025f",
                    "on-surface": "#1b1c1c",
                    "on-tertiary": "#ffffff",
                    "tertiary-fixed": "#f2daff",
                    "on-primary-fixed": "#001945",
                    "on-secondary-fixed": "#001b3c",
                    "on-error": "#ffffff",
                    "surface-container": "#f0eded",
                    "secondary-container": "#72a9ff",
                    "surface": "#fbf9f8",
                    "on-primary-fixed-variant": "#1a438e",
                    "secondary-fixed": "#d6e3ff",
                    "primary-container": "#003580",
                    "surface-container-highest": "#e4e2e2",
                    "error-container": "#ffdad6",
                    "on-tertiary-container": "#c08ee9",
                    "surface-bright": "#fbf9f8",
                    "outline-variant": "#c4c6d3",
                    "secondary": "#175eae",
                    "on-background": "#1b1c1c",
                    "on-secondary-container": "#003d79",
                    "inverse-on-surface": "#f2f0f0",
                    "tertiary-container": "#502176",
                    "on-primary-container": "#7fa1f2",
                    "inverse-primary": "#b0c6ff",
                    "background": "#fbf9f8",
                    "on-primary": "#ffffff",
                    "error": "#ba1a1a",
                    "surface-variant": "#e4e2e2",
                    "surface-container-low": "#f5f3f3",
                    "on-tertiary-fixed": "#2d004f",
                    "on-tertiary-fixed-variant": "#5e3085",
                    "tertiary-fixed-dim": "#e0b7ff",
                    "on-surface-variant": "#434651",
                    "on-secondary": "#ffffff",
                    "surface-tint": "#375ca8",
                    "surface-dim": "#dbd9d9",
                    "surface-container-lowest": "#ffffff",
                    "primary-fixed-dim": "#b0c6ff",
                    "secondary-fixed-dim": "#a8c8ff",
                    "outline": "#747782",
                    "on-secondary-fixed-variant": "#00468a",
                    "inverse-surface": "#303030",
                    "surface-container-high": "#eae8e7",
                    "primary-fixed": "#d9e2ff",
                    "on-error-container": "#93000a",
                    "primary": "#002155"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                "spacing": {
                    "container-max": "1320px",
                    "section-padding-desktop": "5rem",
                    "section-padding-mobile": "2.5rem",
                    "base-unit": "8px",
                    "gutter": "1.5rem"
                },
                "fontFamily": {
                    "headline-lg": ["Poppins"],
                    "headline-md": ["Poppins"],
                    "label-md": ["Poppins"],
                    "body-md": ["Poppins"],
                    "label-sm": ["Poppins"],
                    "body-lg": ["Poppins"],
                    "display-lg": ["Poppins"]
                },
                "fontSize": {
                    "headline-lg": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "headline-md": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "label-md": ["14px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}]
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
    /* Mosaic grid — 4 kolom, 2 baris tetap 300px */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(2, 300px);
        gap: 1.5rem;
    }
    .slot-1 { grid-column: 1 / 2; grid-row: 1 / 3; }
    .slot-2 { grid-column: 2 / 3; grid-row: 1 / 2; }
    .slot-3 { grid-column: 2 / 3; grid-row: 2 / 3; }
    .slot-4 { grid-column: 3 / 5; grid-row: 1 / 2; }
    .slot-5 { grid-column: 3 / 4; grid-row: 2 / 3; }
    .slot-6 { grid-column: 4 / 5; grid-row: 2 / 3; }

    @media (max-width: 1024px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: auto;
        }
        .slot-1, .slot-2, .slot-3,
        .slot-4, .slot-5, .slot-6 {
            grid-column: auto;
            grid-row: auto;
            min-height: 300px;
        }
    }
    @media (max-width: 640px) {
        .gallery-grid { grid-template-columns: 1fr; }
    }

    /* Fallback grid untuk item ke-7+ (chunk berikutnya) */
    .gallery-grid-simple {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .gallery-grid-simple { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .gallery-grid-simple { grid-template-columns: 1fr; }
    }
</style>
</head>
<body class="bg-background text-on-background font-body-md">

<!-- Breadcrumb & Header -->
<section class="relative py-24 overflow-hidden bg-primary">
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <img
            alt="background"
            class="w-full h-full object-cover"
            src="{{ asset('assets/img/galeri.jpg') }}"
        />
    </div>
    <div class="absolute -right-20 -top-20 text-tertiary-fixed/10 select-none">
        <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">eco</span>
    </div>
    <div class="relative px-gutter max-w-container-max mx-auto text-center md:text-left">
        <nav class="flex items-center gap-2 text-white/80 font-label-sm mb-4">
            <a class="hover:text-white transition-colors" href="{{ route('welcome.guest') }}">Beranda</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-tertiary-fixed font-bold">Album Galeri</span>
        </nav>
        <h1 class="text-white font-display-lg text-display-lg">Semua Album Galeri</h1>
        <p class="text-white/70 max-w-2xl mt-4 font-body-lg text-body-lg">
            Dokumentasi kegiatan penelitian, pengabdian masyarakat, dan inovasi civitas akademika Institut Teknologi Del.
        </p>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-section-padding-desktop px-gutter max-w-container-max mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
            <span class="text-secondary font-bold font-label-md tracking-wider">VISUAL ARCHIVE</span>
            <h2 class="text-headline-lg font-headline-lg mt-2">Kilas Balik Kegiatan</h2>
        </div>
    </div>

    {{-- Loop per 6 album, chunk pertama pakai mosaic, selanjutnya grid biasa --}}
    @foreach ($allGalleries->chunk(6) as $chunkIndex => $galleries)

        @if ($chunkIndex === 0)
        {{-- === CHUNK PERTAMA: MOSAIC LAYOUT === --}}
        <div class="gallery-grid mb-8">

            {{-- Slot 1: Portrait besar --}}
            @if(isset($galleries[0]))
            <a href="{{ route('guest.gallery.detail', $galleries[0]->id) }}"
               class="slot-1 group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300">
                <img
                    src="{{ asset('storage/' . $galleries[0]->cover_image) }}"
                    alt="{{ $galleries[0]->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-0 left-0 right-0 p-6 bg-white flex items-center justify-between">
                    <div>
                        <span class="text-label-sm text-secondary font-bold uppercase">Galeri</span>
                        <h3 class="font-headline-md text-headline-md leading-tight text-on-surface">{{ $galleries[0]->title }}</h3>
                    </div>
                    <div class="bg-secondary text-white p-3 rounded-lg group-hover:bg-tertiary transition-colors shrink-0">
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </div>
                </div>
            </a>
            @endif

            {{-- Slot 2 & 3: Kotak kecil --}}
            @if(isset($galleries[1]))
            <a href="{{ route('guest.gallery.detail', $galleries[1]->id) }}"
               class="slot-2 group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300">
                <img
                    src="{{ asset('storage/' . $galleries[1]->cover_image) }}"
                    alt="{{ $galleries[1]->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/95 backdrop-blur-sm rounded-lg flex items-center justify-between">
                    <h3 class="font-label-md text-on-surface truncate">{{ $galleries[1]->title }}</h3>
                    <span class="material-symbols-outlined text-secondary shrink-0 ml-2">arrow_forward</span>
                </div>
            </a>
            @endif

            @if(isset($galleries[2]))
            <a href="{{ route('guest.gallery.detail', $galleries[2]->id) }}"
               class="slot-3 group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300">
                <img
                    src="{{ asset('storage/' . $galleries[2]->cover_image) }}"
                    alt="{{ $galleries[2]->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/95 backdrop-blur-sm rounded-lg flex items-center justify-between">
                    <h3 class="font-label-md text-on-surface truncate">{{ $galleries[2]->title }}</h3>
                    <span class="material-symbols-outlined text-secondary shrink-0 ml-2">arrow_forward</span>
                </div>
            </a>
            @endif

            {{-- Slot 4: Landscape lebar --}}
            @if(isset($galleries[3]))
            <a href="{{ route('guest.gallery.detail', $galleries[3]->id) }}"
               class="slot-4 group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300">
                <img
                    src="{{ asset('storage/' . $galleries[3]->cover_image) }}"
                    alt="{{ $galleries[3]->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/80 to-transparent text-white">
                    <span class="text-label-sm text-secondary-container font-bold uppercase">Galeri</span>
                    <div class="flex justify-between items-end">
                        <h3 class="font-headline-md text-headline-md">{{ $galleries[3]->title }}</h3>
                        <div class="bg-white text-secondary p-4 rounded-full group-hover:scale-110 transition-transform shrink-0 ml-4">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </div>
                    </div>
                </div>
            </a>
            @endif

            {{-- Slot 5 & 6: Medium --}}
            @if(isset($galleries[4]))
            <a href="{{ route('guest.gallery.detail', $galleries[4]->id) }}"
               class="slot-5 group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300">
                <img
                    src="{{ asset('storage/' . $galleries[4]->cover_image) }}"
                    alt="{{ $galleries[4]->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/95 backdrop-blur-sm rounded-lg flex items-center justify-between">
                    <h3 class="font-label-md text-on-surface truncate">{{ $galleries[4]->title }}</h3>
                    <span class="material-symbols-outlined text-secondary shrink-0 ml-2">arrow_forward</span>
                </div>
            </a>
            @endif

            @if(isset($galleries[5]))
            <a href="{{ route('guest.gallery.detail', $galleries[5]->id) }}"
               class="slot-6 group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300">
                <img
                    src="{{ asset('storage/' . $galleries[5]->cover_image) }}"
                    alt="{{ $galleries[5]->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/95 backdrop-blur-sm rounded-lg flex items-center justify-between">
                    <h3 class="font-label-md text-on-surface truncate">{{ $galleries[5]->title }}</h3>
                    <span class="material-symbols-outlined text-secondary shrink-0 ml-2">arrow_forward</span>
                </div>
            </a>
            @endif

        </div>
        {{-- === END MOSAIC === --}}

        @else
        {{-- === CHUNK BERIKUTNYA: GRID 3 KOLOM BIASA === --}}
        <div class="gallery-grid-simple mb-8">
            @foreach($galleries as $gallery)
            <a href="{{ route('guest.gallery.detail', $gallery->id) }}"
               class="group relative overflow-hidden rounded-xl border border-outline-variant shadow-sm hover:shadow-lg transition-all duration-300 min-h-[260px]">
                <img
                    src="{{ asset('storage/' . $gallery->cover_image) }}"
                    alt="{{ $gallery->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/95 backdrop-blur-sm rounded-lg flex items-center justify-between">
                    <h3 class="font-label-md text-on-surface truncate">{{ $gallery->title }}</h3>
                    <span class="material-symbols-outlined text-secondary shrink-0 ml-2">arrow_forward</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    @endforeach

    {{-- Empty State --}}
    @if($allGalleries->isEmpty())
    <div class="flex flex-col items-center gap-4 py-24 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl opacity-30">photo_library</span>
        <p class="font-label-md">Belum ada album galeri yang tersedia.</p>
    </div>
    @endif

    {{-- Pagination --}}
    <div class="mt-16 flex justify-center">
        {{ $allGalleries->links('pagination::bootstrap-5') }}
    </div>

</section>

<!-- Footer -->


<script>
    document.querySelectorAll('.group').forEach(item => {
        item.addEventListener('mouseenter', () => {
            const icon = item.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.style.transform = 'translateX(5px)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
        item.addEventListener('mouseleave', () => {
            const icon = item.querySelector('.material-symbols-outlined');
            if (icon) icon.style.transform = 'translateX(0)';
        });
    });

    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (!header) return;
        if (window.scrollY > 50) {
            header.classList.add('py-2', 'bg-secondary/95', 'backdrop-blur-md');
        } else {
            header.classList.remove('py-2', 'bg-secondary/95', 'backdrop-blur-md');
        }
    });
</script>

</body>
</html>

@endsection



