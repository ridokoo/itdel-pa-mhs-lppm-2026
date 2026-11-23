@extends('layouts.guest_master')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Profil LPPM IT Del - Institut Teknologi Del</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "tertiary-fixed": "#f2daff",
                    "on-primary-fixed": "#001945",
                    "on-tertiary": "#ffffff",
                    "tertiary": "#39025f",
                    "on-surface": "#1b1c1c",
                    "secondary-fixed": "#d6e3ff",
                    "primary-container": "#003580",
                    "surface": "#fbf9f8",
                    "on-primary-fixed-variant": "#1a438e",
                    "surface-container": "#f0eded",
                    "secondary-container": "#72a9ff",
                    "on-secondary-fixed": "#001b3c",
                    "on-error": "#ffffff",
                    "on-secondary-container": "#003d79",
                    "secondary": "#175eae",
                    "on-background": "#1b1c1c",
                    "surface-bright": "#fbf9f8",
                    "outline-variant": "#c4c6d3",
                    "surface-container-highest": "#e4e2e2",
                    "error-container": "#ffdad6",
                    "on-tertiary-container": "#c08ee9",
                    "on-primary": "#ffffff",
                    "error": "#ba1a1a",
                    "background": "#fbf9f8",
                    "on-primary-container": "#7fa1f2",
                    "inverse-primary": "#b0c6ff",
                    "inverse-on-surface": "#f2f0f0",
                    "tertiary-container": "#502176",
                    "on-tertiary-fixed-variant": "#5e3085",
                    "surface-variant": "#e4e2e2",
                    "surface-container-low": "#f5f3f3",
                    "on-tertiary-fixed": "#2d004f",
                    "on-surface-variant": "#434651",
                    "on-secondary": "#ffffff",
                    "tertiary-fixed-dim": "#e0b7ff",
                    "on-secondary-fixed-variant": "#00468a",
                    "secondary-fixed-dim": "#a8c8ff",
                    "outline": "#747782",
                    "surface-dim": "#dbd9d9",
                    "surface-container-lowest": "#ffffff",
                    "primary-fixed-dim": "#b0c6ff",
                    "surface-tint": "#375ca8",
                    "primary": "#002155",
                    "primary-fixed": "#d9e2ff",
                    "on-error-container": "#93000a",
                    "inverse-surface": "#303030",
                    "surface-container-high": "#eae8e7"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                "spacing": {
                    "section-padding-desktop": "5rem",
                    "container-max": "1320px",
                    "section-padding-mobile": "2.5rem",
                    "base-unit": "8px",
                    "gutter": "1.5rem"
                },
                "fontFamily": {
                    "headline-md": ["Poppins"],
                    "headline-lg": ["Poppins"],
                    "display-lg": ["Poppins"],
                    "label-sm": ["Poppins"],
                    "body-lg": ["Poppins"],
                    "label-md": ["Poppins"],
                    "body-md": ["Poppins"]
                },
                "fontSize": {
                    "headline-md": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
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
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .racing-green-overlay {
        background-color: rgba(0, 53, 128, 0.85);
    }
    .card-hover {
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), box-shadow 0.3s ease;
    }
    .card-hover:hover { transform: translateY(-6px); }
</style>
</head>
<body class="bg-background text-on-background font-body-md">

<!-- Breadcrumb & Header Profil -->
<section class="relative py-24 overflow-hidden bg-primary">
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <img
            alt="background"
            class="w-full h-full object-cover"
            src="{{ asset('assets/img/profil.jpg') }}"
        />
    </div>
    <!-- Ikon Dekorasi Latar Belakang -->
    <div class="absolute -right-20 -top-20 text-tertiary-fixed/10 select-none">
        <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">groups</span>
    </div>
    <div class="relative px-gutter max-w-container-max mx-auto text-center md:text-left">
        <nav class="flex items-center gap-2 text-white/80 font-label-sm mb-4">
            <a class="hover:text-white transition-colors" href="{{ route('welcome.guest') }}">Beranda</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-tertiary-fixed font-bold">Profil</span>
        </nav>
        <h1 class="text-white font-display-lg text-display-lg">Profil LPPM IT Del</h1>
        <p class="text-white/70 max-w-2xl mt-4 font-body-lg text-body-lg">
            Mengenal lebih dekat Lembaga Penelitian dan Pengabdian kepada Masyarakat Institut Teknologi Del beserta visi, misi, dan struktur organisasinya.
        </p>
    </div>
</section>

<!-- Profile Grid -->
<section class="py-16 md:py-24 max-w-container-max mx-auto px-section-padding-mobile md:px-section-padding-desktop">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">

        @foreach($allProfiles as $item)
        <div class="card-hover bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md group">

            {{-- Gambar --}}
            <a href="{{ route('guest.profile.detail', $item->id) }}" class="relative h-56 overflow-hidden block">
                <img
                    src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/img/blog/blog-1.jpg') }}"
                    alt="{{ $item->title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                {{-- Badge content_type — logika label persis dari template lama --}}
                <div class="absolute top-4 left-4">
                    <span class="bg-tertiary text-on-tertiary font-label-sm text-label-sm px-3 py-1 rounded-full uppercase tracking-wider">
                        @if($item->content_type == 'strategic_goals')
                            Tujuan Strategis
                        @elseif($item->content_type == 'organization_structure')
                            Struktur Organisasi
                        @else
                            {{ ucwords(str_replace('_', ' ', $item->content_type)) }}
                        @endif
                    </span>
                </div>
            </a>

            {{-- Konten --}}
            <div class="p-6">
                <a
                    href="{{ route('guest.profile.detail', $item->id) }}"
                    class="block font-headline-md text-headline-md text-primary mb-3 hover:text-secondary transition-colors"
                >
                    {{ $item->title }}
                </a>

                <p class="text-on-surface-variant font-body-md text-body-md line-clamp-2">
                    {{ $item->content }}
                </p>

                <div class="mt-6 pt-4 border-t border-outline-variant">
                    <a
                        href="{{ route('guest.profile.detail', $item->id) }}"
                        class="font-label-sm text-label-sm text-primary flex items-center gap-1 hover:text-secondary transition-colors group/link"
                    >
                        Pelajari Selengkapnya
                        <span class="material-symbols-outlined text-sm group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>

<!-- Footer -->


</body>
</html>

@endsection



