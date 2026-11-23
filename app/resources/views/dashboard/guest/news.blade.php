@extends('layouts.guest_master')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Berita &amp; Kegiatan - LPPM IT Del</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "on-tertiary-container": "#c08ee9",
                    "surface-container-lowest": "#ffffff",
                    "on-primary-fixed": "#001945",
                    "inverse-on-surface": "#f2f0f0",
                    "surface-container-highest": "#e4e2e2",
                    "surface-container": "#f0eded",
                    "on-primary": "#ffffff",
                    "primary-fixed-dim": "#b0c6ff",
                    "primary-container": "#003580",
                    "outline": "#747782",
                    "on-background": "#1b1c1c",
                    "tertiary": "#39025f",
                    "secondary-fixed-dim": "#a8c8ff",
                    "on-secondary": "#ffffff",
                    "on-primary-fixed-variant": "#1a438e",
                    "secondary": "#175eae",
                    "on-error-container": "#93000a",
                    "surface-bright": "#fbf9f8",
                    "on-tertiary-fixed": "#2d004f",
                    "surface-tint": "#375ca8",
                    "surface": "#fbf9f8",
                    "error-container": "#ffdad6",
                    "on-secondary-fixed": "#001b3c",
                    "surface-container-high": "#eae8e7",
                    "error": "#ba1a1a",
                    "primary-fixed": "#d9e2ff",
                    "outline-variant": "#c4c6d3",
                    "inverse-primary": "#b0c6ff",
                    "on-secondary-fixed-variant": "#00468a",
                    "surface-variant": "#e4e2e2",
                    "on-surface-variant": "#434651",
                    "tertiary-container": "#502176",
                    "primary": "#002155",
                    "on-surface": "#1b1c1c",
                    "tertiary-fixed": "#f2daff",
                    "secondary-container": "#72a9ff",
                    "on-secondary-container": "#003d79",
                    "on-error": "#ffffff",
                    "background": "#fbf9f8",
                    "tertiary-fixed-dim": "#e0b7ff",
                    "on-tertiary": "#ffffff",
                    "on-primary-container": "#7fa1f2",
                    "surface-dim": "#dbd9d9",
                    "inverse-surface": "#303030",
                    "secondary-fixed": "#d6e3ff",
                    "surface-container-low": "#f5f3f3",
                    "on-tertiary-fixed-variant": "#5e3085"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                "spacing": {
                    "section-padding-desktop": "5rem",
                    "section-padding-mobile": "2.5rem",
                    "base-unit": "8px",
                    "gutter": "1.5rem",
                    "container-max": "1320px"
                },
                "fontFamily": {
                    "label-md": ["Poppins"],
                    "label-sm": ["Poppins"],
                    "body-md": ["Poppins"],
                    "body-lg": ["Poppins"],
                    "headline-md": ["Poppins"],
                    "headline-lg": ["Poppins"],
                    "display-lg": ["Poppins"]
                },
                "fontSize": {
                    "label-md": ["14px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-md": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                }
            }
        }
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block; line-height: 1;
    }
    .racing-green-overlay {
        background: linear-gradient(rgba(0,53,128,0.85), rgba(0,33,85,0.95));
    }
    .leaf-pattern {
        background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0);
        background-size: 24px 24px;
    }
    .service-card-style1 {
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), box-shadow 0.3s ease;
    }
    .service-card-style1:hover { transform: translateY(-8px); }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</head>
<body class="bg-background text-on-surface font-body-md">

<!-- Breadcrumb & Header Berita -->
<section class="relative py-24 overflow-hidden bg-primary">
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <img
            alt="background"
            class="w-full h-full object-cover"
            src="{{ asset('assets/img/berita.jpg') }}"
        />
    </div>
    <!-- Ikon Dekorasi Latar Belakang -->
    <div class="absolute -right-20 -top-20 text-tertiary-fixed/10 select-none">
        <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">newspaper</span>
    </div>
    <div class="relative px-gutter max-w-container-max mx-auto text-center md:text-left">
        <nav class="flex items-center gap-2 text-white/80 font-label-sm mb-4">
            <a class="hover:text-white transition-colors" href="{{ route('welcome.guest') }}">Beranda</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-tertiary-fixed font-bold">News</span>
        </nav>
        <h1 class="text-white font-display-lg text-display-lg">Berita & Kegiatan</h1>
        <p class="text-white/70 max-w-2xl mt-4 font-body-lg text-body-lg">
            Pantau perkembangan riset, inovasi, dan kegiatan pengabdian masyarakat terbaru di lingkungan Institut Teknologi Del secara transparan dan akurat.
        </p>
    </div>
</section>

<!-- Filter & Search Bar -->
<section class="bg-surface-container-low border-b border-outline-variant sticky top-16 z-40">
    <div class="max-w-container-max mx-auto px-4 md:px-section-padding-desktop py-6">
        <div class="flex flex-col md:flex-row gap-6 items-center justify-between">

            {{-- View toggle --}}
            <div class="flex items-center bg-white rounded-lg p-1 border border-outline-variant shadow-sm" id="view-toggle">
                <button class="p-2 rounded-md bg-secondary text-white shadow-sm transition-all" title="Grid View" data-view="grid">
                    <span class="material-symbols-outlined block">grid_view</span>
                </button>
                <button class="p-2 rounded-md text-on-surface-variant hover:bg-surface-container transition-all" title="List View" data-view="list">
                    <span class="material-symbols-outlined block">format_list_bulleted</span>
                </button>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('guest.news.index') }}" class="relative w-full md:max-w-xl">
                <input
                    name="q"
                    value="{{ request('q') }}"
                    class="w-full pl-12 pr-24 py-3 rounded-lg border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary transition-all font-body-md bg-white"
                    placeholder="Cari berdasarkan kata kunci..."
                    type="text"
                />
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-secondary text-on-secondary px-4 py-1.5 rounded-md font-label-md text-label-md hover:bg-opacity-90 transition-all">
                    Cari
                </button>
            </form>

            {{-- Sort --}}
            <div class="flex items-center gap-3 w-full md:w-auto">
                <label class="font-label-md text-label-md text-on-surface-variant whitespace-nowrap">Urutkan:</label>
                <select class="w-full md:w-48 py-2.5 px-4 rounded-lg border border-outline-variant font-label-md text-label-md focus:border-secondary focus:ring-1 focus:ring-secondary bg-white">
                    <option>Terbaru</option>
                    <option>Terpopuler</option>
                    <option>Paling Banyak Dilihat</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- News Grid -->
<section class="py-12 md:py-20 max-w-container-max mx-auto px-4 md:px-section-padding-desktop">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="news-grid">

        @forelse($news as $item)
        <article class="service-card-style1 flex flex-col bg-white rounded-xl overflow-hidden border border-outline-variant shadow-sm">

            {{-- Gambar --}}
            <a href="{{ route('guest.news.detail', $item->id) }}" class="relative h-[250px] overflow-hidden block">
                <img
                    src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/img/service/service-4.jpg') }}"
                    alt="{{ $item->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                />
                {{-- Badge tanggal --}}
                <div class="absolute top-4 left-4 bg-secondary text-white p-3 rounded-lg flex flex-col items-center justify-center min-w-[60px] shadow-md">
                    <span class="font-label-md text-label-md leading-none">
                        {{ \Carbon\Carbon::parse($item->event_date)->format('d') }}
                    </span>
                    <span class="font-label-sm text-[10px] uppercase">
                        {{ \Carbon\Carbon::parse($item->event_date)->translatedFormat('M Y') }}
                    </span>
                </div>
            </a>

            {{-- Konten --}}
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex items-center gap-2 text-secondary mb-3">
                    <span class="material-symbols-outlined text-[20px]">newspaper</span>
                    <span class="font-label-sm text-label-sm font-bold uppercase tracking-wider">Berita</span>
                </div>

                <h3 class="font-headline-md text-headline-md mb-3 hover:text-secondary transition-colors">
                    <a href="{{ route('guest.news.detail', $item->id) }}">{{ $item->title }}</a>
                </h3>

                <p class="font-body-md text-body-md text-on-surface-variant line-clamp-2 mb-6">
                    {{ $item->content }}
                </p>

                <div class="mt-auto pt-6 border-t border-surface-container flex items-center justify-between">
                    <a
                        href="{{ route('guest.news.detail', $item->id) }}"
                        class="font-label-md text-label-md text-secondary hover:underline flex items-center gap-1 group"
                    >
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </article>

        @empty
        <div class="col-span-3 text-center py-24 text-on-surface-variant">
            <span class="material-symbols-outlined text-6xl opacity-30 block mb-4">newspaper</span>
            <p class="font-label-md">Belum ada berita yang diterbitkan saat ini.</p>
        </div>
        @endforelse

    </div>
</section>

<!-- Pagination -->
@if($news->hasPages())
<section class="pb-20 max-w-container-max mx-auto px-4">
    <nav class="flex items-center justify-center gap-2">

        {{-- Prev --}}
        @if($news->onFirstPage())
        <span class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-outline opacity-40 cursor-not-allowed">
            <span class="material-symbols-outlined">chevron_left</span>
        </span>
        @else
        <a href="{{ $news->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-outline hover:bg-secondary hover:text-on-secondary transition-all">
            <span class="material-symbols-outlined">chevron_left</span>
        </a>
        @endif

        {{-- Nomor halaman --}}
        @foreach($news->getUrlRange(1, $news->lastPage()) as $page => $url)
        <a
            href="{{ $url }}"
            class="w-10 h-10 flex items-center justify-center rounded-lg font-label-md transition-all
                {{ $page == $news->currentPage()
                    ? 'bg-secondary text-on-secondary shadow-md'
                    : 'border border-outline-variant text-on-surface-variant hover:bg-surface-container' }}"
        >{{ $page }}</a>
        @endforeach

        {{-- Next --}}
        @if($news->hasMorePages())
        <a href="{{ $news->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-outline hover:bg-secondary hover:text-on-secondary transition-all">
            <span class="material-symbols-outlined">chevron_right</span>
        </a>
        @else
        <span class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-outline opacity-40 cursor-not-allowed">
            <span class="material-symbols-outlined">chevron_right</span>
        </span>
        @endif

    </nav>
</section>
@endif



<script>
    // View toggle: grid vs list
    const grid = document.getElementById('news-grid');
    document.querySelectorAll('#view-toggle button').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#view-toggle button').forEach(b => {
                b.classList.remove('bg-secondary', 'text-white', 'shadow-sm');
                b.classList.add('text-on-surface-variant');
            });
            btn.classList.add('bg-secondary', 'text-white', 'shadow-sm');
            btn.classList.remove('text-on-surface-variant');

            if (btn.dataset.view === 'list') {
                grid.classList.remove('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
                grid.classList.add('grid-cols-1');
                grid.querySelectorAll('article').forEach(card => {
                    card.classList.add('flex-row');
                    card.classList.remove('flex-col');
                    const img = card.querySelector('a.block');
                    if (img) { img.classList.add('w-64', 'h-auto'); img.classList.remove('h-[250px]'); }
                });
            } else {
                grid.classList.add('md:grid-cols-2', 'lg:grid-cols-3');
                grid.querySelectorAll('article').forEach(card => {
                    card.classList.remove('flex-row');
                    card.classList.add('flex-col');
                    const img = card.querySelector('a.block');
                    if (img) { img.classList.remove('w-64', 'h-auto'); img.classList.add('h-[250px]'); }
                });
            }
        });
    });

    // Card click opacity micro-interaction
    document.querySelectorAll('.service-card-style1').forEach(card => {
        card.addEventListener('click', () => {
            card.style.opacity = '0.8';
            setTimeout(() => card.style.opacity = '1', 100);
        });
    });
</script>

</body>
</html>

@endsection



