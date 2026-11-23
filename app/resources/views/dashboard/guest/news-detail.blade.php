@extends('layouts.guest_master')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $item->title }} - LPPM IT Del</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "outline": "#747782",
                    "tertiary": "#39025f",
                    "primary": "#002155",
                    "on-background": "#1b1c1c",
                    "background": "#fbf9f8",
                    "error": "#ba1a1a",
                    "on-error": "#ffffff",
                    "surface-dim": "#dbd9d9",
                    "surface-container-highest": "#e4e2e2",
                    "on-tertiary-fixed": "#2d004f",
                    "primary-fixed": "#d9e2ff",
                    "error-container": "#ffdad6",
                    "secondary-fixed-dim": "#a8c8ff",
                    "secondary-fixed": "#d6e3ff",
                    "tertiary-container": "#502176",
                    "surface-container-high": "#eae8e7",
                    "on-error-container": "#93000a",
                    "inverse-surface": "#303030",
                    "surface-variant": "#e4e2e2",
                    "on-surface-variant": "#434651",
                    "on-primary": "#ffffff",
                    "surface-tint": "#375ca8",
                    "on-secondary-container": "#003d79",
                    "surface-container-lowest": "#ffffff",
                    "on-secondary-fixed": "#001b3c",
                    "primary-container": "#003580",
                    "on-surface": "#1b1c1c",
                    "inverse-primary": "#b0c6ff",
                    "tertiary-fixed-dim": "#e0b7ff",
                    "secondary": "#175eae",
                    "on-tertiary-container": "#c08ee9",
                    "primary-fixed-dim": "#b0c6ff",
                    "on-secondary-fixed-variant": "#00468a",
                    "surface-bright": "#fbf9f8",
                    "surface-container-low": "#f5f3f3",
                    "surface-container": "#f0eded",
                    "on-tertiary-fixed-variant": "#5e3085",
                    "inverse-on-surface": "#f2f0f0",
                    "secondary-container": "#72a9ff",
                    "on-primary-fixed-variant": "#1a438e",
                    "on-primary-fixed": "#001945",
                    "on-secondary": "#ffffff",
                    "on-primary-container": "#7fa1f2",
                    "tertiary-fixed": "#f2daff",
                    "outline-variant": "#c4c6d3",
                    "surface": "#fbf9f8",
                    "on-tertiary": "#ffffff",
                    "narvik": "#fdfaf7",
                    "peppermint": "#f0f7f4"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem",
                    "news": "20px"
                },
                "spacing": {
                    "gutter": "1.5rem",
                    "section-padding-desktop": "5rem",
                    "section-padding-mobile": "2.5rem",
                    "base-unit": "8px",
                    "container-max": "1320px"
                },
                "fontFamily": {
                    "body-lg": ["Poppins"],
                    "label-md": ["Poppins"],
                    "headline-md": ["Poppins"],
                    "headline-lg": ["Poppins"],
                    "body-md": ["Poppins"],
                    "label-sm": ["Poppins"],
                    "display-lg": ["Poppins"]
                },
                "fontSize": {
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "headline-md": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                }
            }
        }
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        line-height: 1;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</head>
<body class="bg-background font-body-md text-on-surface">

<!-- Hero Section (News Content) -->
<section class="bg-narvik py-16 px-section-padding-mobile md:px-section-padding-desktop">
    <div class="max-w-container-max mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

        <!-- Kolom Kiri: Gambar + Badge -->
        <div class="flex flex-col gap-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-label-sm font-label-sm text-on-surface-variant">
                <a href="{{ route('welcome.guest') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('guest.news.index') }}" class="hover:text-primary transition-colors">News</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold truncate max-w-[200px]">{{ Str::limit($item->title, 30) }}</span>
            </nav>

            {{-- Gambar utama --}}
            <div class="relative group">
                <img
                    src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/img/hero/hero-img-3.png') }}"
                    alt="{{ $item->title }}"
                    class="w-full h-[550px] object-cover rounded-news shadow-lg transition-transform duration-500 group-hover:scale-[1.01]"
                />
            </div>

            {{-- Badge --}}
            <div class="flex gap-4 items-center flex-wrap">
                <div class="flex items-center gap-2 px-4 py-2 bg-[#90be6d] text-white rounded-lg shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">newspaper</span>
                    <span class="font-label-sm text-label-sm">News</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-[#f4e4bc] text-[#5e503f] rounded-lg shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">school</span>
                    <span class="font-label-sm text-label-sm">IT Del</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-[#f3d5d9] text-[#6d5959] rounded-lg shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span class="font-label-sm text-label-sm">Official</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Konten Berita -->
        <div class="flex flex-col">

            {{-- Tanggal --}}
            <div class="flex items-center gap-3 mb-4 text-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_tree</span>
                <span class="font-label-md text-label-md tracking-wider uppercase">
                    {{ \Carbon\Carbon::parse($item->event_date)->translatedFormat('d F Y') }}
                </span>
            </div>

            {{-- Judul --}}
            <h1 class="font-display-lg text-[2.8rem] leading-[1.2] text-primary mb-8">
                {{ $item->title }}
            </h1>

            {{-- Isi berita --}}
            <div class="font-body-lg text-body-lg text-on-surface text-justify leading-[1.8] space-y-6 mb-12">
                {!! nl2br(e($item->content)) !!}
            </div>

            {{-- Tombol kembali --}}
            <a
                href="{{ route('guest.news.index') }}"
                class="w-fit flex items-center gap-3 bg-primary text-white px-8 py-4 rounded-lg font-label-md text-label-md hover:bg-secondary transition-all active:scale-95 shadow-md"
            >
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali ke News
            </a>
        </div>
    </div>
</section>

<!-- Berita Lainnya -->
<section class="bg-peppermint py-20 px-section-padding-mobile md:px-section-padding-desktop">
    <div class="max-w-container-max mx-auto">

        <div class="mb-12">
            <span class="font-label-md text-label-md text-secondary tracking-widest uppercase">Rekomendasi</span>
            <h2 class="font-headline-lg text-headline-lg text-primary mt-2">Berita Lainnya</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($otherNews as $index => $other)
            <a
                href="{{ route('guest.news.detail', $other->id) }}"
                class="promo-item group flex items-start gap-5 p-6 bg-white rounded-xl border border-outline-variant hover:shadow-lg transition-all"
            >
                {{-- Ikon sesuai urutan seperti template lama --}}
                <div class="min-w-[65px] h-[65px] bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center rounded-full group-hover:bg-primary group-hover:text-white transition-colors shrink-0">
                    @if($index == 0)
                        <span class="material-symbols-outlined text-[30px]">description</span>
                    @elseif($index == 1)
                        <span class="material-symbols-outlined text-[30px]">campaign</span>
                    @else
                        <span class="material-symbols-outlined text-[30px]">menu_book</span>
                    @endif
                </div>

                <div class="min-w-0">
                    <h3 class="font-label-md text-label-md text-primary group-hover:text-secondary transition-colors mb-2 line-clamp-2">
                        {{ $other->title }}
                    </h3>
                    <p class="font-body-md text-label-sm text-outline line-clamp-2">
                        {{ Str::limit($other->content, 80) }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>



<script>
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (!header) return;
        header.classList.toggle('shadow-md', window.scrollY > 50);
        header.classList.toggle('shadow-sm', window.scrollY <= 50);
    });

    document.querySelectorAll('.promo-item').forEach(item => {
        item.addEventListener('click', () => {
            item.style.transform = 'scale(0.98)';
            setTimeout(() => item.style.transform = 'scale(1)', 100);
        });
    });
</script>

</body>
</html>

@endsection



