@extends('layouts.guest_master')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ $category->name }} - LPPM IT Del</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "surface-container-low": "#f5f3f3",
                    "on-surface-variant": "#434651",
                    "on-secondary-fixed-variant": "#00468a",
                    "on-primary-fixed-variant": "#1a438e",
                    "on-primary-fixed": "#001945",
                    "outline-variant": "#c4c6d3",
                    "on-surface": "#1b1c1c",
                    "on-secondary-container": "#003d79",
                    "surface-container": "#f0eded",
                    "on-secondary-fixed": "#001b3c",
                    "surface-bright": "#fbf9f8",
                    "inverse-surface": "#303030",
                    "secondary-container": "#72a9ff",
                    "on-secondary": "#ffffff",
                    "surface": "#fbf9f8",
                    "on-primary": "#ffffff",
                    "on-tertiary-container": "#c08ee9",
                    "primary": "#002155",
                    "on-tertiary": "#ffffff",
                    "surface-container-high": "#eae8e7",
                    "tertiary-fixed": "#f2daff",
                    "on-tertiary-fixed": "#2d004f",
                    "surface-tint": "#375ca8",
                    "surface-container-lowest": "#ffffff",
                    "outline": "#747782",
                    "primary-fixed-dim": "#b0c6ff",
                    "tertiary": "#39025f",
                    "on-tertiary-fixed-variant": "#5e3085",
                    "on-error-container": "#93000a",
                    "surface-container-highest": "#e4e2e2",
                    "secondary": "#175eae",
                    "error-container": "#ffdad6",
                    "secondary-fixed": "#d6e3ff",
                    "primary-container": "#003580",
                    "on-primary-container": "#7fa1f2",
                    "inverse-primary": "#b0c6ff",
                    "surface-variant": "#e4e2e2",
                    "tertiary-fixed-dim": "#e0b7ff",
                    "inverse-on-surface": "#f2f0f0",
                    "error": "#ba1a1a",
                    "background": "#fbf9f8",
                    "on-error": "#ffffff",
                    "tertiary-container": "#502176",
                    "primary-fixed": "#d9e2ff",
                    "secondary-fixed-dim": "#a8c8ff",
                    "on-background": "#1b1c1c",
                    "surface-dim": "#dbd9d9",
                    // "racing-green": "#158771",
                    "peppermint": "#f2f9f8"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem",
                    "card": "20px"
                },
                "spacing": {
                    "section-padding-mobile": "2.5rem",
                    "gutter": "1.5rem",
                    "container-max": "1320px",
                    "section-padding-desktop": "5rem",
                    "base-unit": "8px"
                },
                "fontFamily": {
                    "display-lg": ["Poppins"],
                    "body-md": ["Poppins"],
                    "body-lg": ["Poppins"],
                    "headline-md": ["Poppins"],
                    "label-sm": ["Poppins"],
                    "label-md": ["Poppins"],
                    "headline-lg-mobile": ["Poppins"],
                    "headline-lg": ["Poppins"]
                },
                "fontSize": {
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-md": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "label-md": ["14px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "headline-lg-mobile": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}]
                }
            }
        }
    }
</script>
<style>
    .glass-overlay {
        background: rgba(0 53 128 / 1)
;
        backdrop-filter: blur(4px);
    }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
</head>
<body class="bg-peppermint font-body-md text-on-surface">

<!-- Breadcrumb & Header Dokumen -->
<section class="relative py-24 overflow-hidden bg-primary">
    <!-- Latar Belakang Gambar (dokumen.JPG) dengan Overlay Biru (Opacity 20) -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <img
            alt="background"
            class="w-full h-full object-cover"
            src="{{ asset('assets/img/dokumen.JPG') }}"
        />
    </div>

    <!-- Ikon Dekorasi Besar di Belakang (Document/Description) -->
    <div class="absolute -right-20 -top-20 text-tertiary-fixed/10 select-none">
        <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'FILL' 1;">description</span>
    </div>

    <!-- Konten Header -->
    <div class="relative px-gutter max-w-container-max mx-auto text-center md:text-left">
        <!-- Breadcrumb Navigasi -->
        <nav class="flex items-center gap-2 text-white/80 font-label-sm mb-4">
            <a class="hover:text-white transition-colors" href="{{ route('welcome.guest') }}">Beranda</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="opacity-80">Dokumen</span>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-tertiary-fixed font-bold">{{ $category->name }}</span>
        </nav>

        <!-- Judul Dinamis (Sesuai Kategori) -->
        <h1 class="text-white font-display-lg text-display-lg">{{ $category->name }}</h1>

        <!-- Kalimat Tambahan Pelengkap -->
        <p class="text-white/70 max-w-2xl mt-4 font-body-lg text-body-lg">
            Akses dan unduh berbagai berkas resmi, berkas panduan administratif, serta template usulan penelitian dan pengabdian masyarakat yang disediakan secara terpusat oleh LPPM Institut Teknologi Del.
        </p>
    </div>
</section>

<!-- Main Content -->
<section class="py-section-padding-desktop px-gutter max-w-container-max mx-auto">

    <!-- Section Header -->
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1 bg-racing-green/10 text-racing-green rounded-full font-label-sm uppercase tracking-wider mb-4">Arsip Dokumen</span>
        <h2 class="font-headline-lg text-headline-lg text-primary mb-4">{{ $category->name }}</h2>
        <p class="max-w-2xl mx-auto text-on-surface-variant font-body-lg text-body-lg">
            Silakan unduh dokumen panduan, berkas administrasi, atau template yang tersedia pada kategori ini secara lengkap.
        </p>
    </div>

    <!-- Info Alert -->
    <div class="max-w-4xl mx-auto mb-10 flex items-center gap-4 p-4 bg-racing-green/5 border border-racing-green/20 rounded-xl text-racing-green">
        <span class="material-symbols-outlined shrink-0">info</span>
        <p class="font-label-md">Klik tombol unduh untuk mendapatkan file dalam format PDF/Doc.</p>
    </div>

    <!-- Document Table -->
    <div class="max-w-5xl mx-auto bg-white rounded-card shadow-[0_2px_15px_rgba(0,0,0,0.05)] overflow-hidden border border-outline-variant">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-racing-green text-white">
                        <th class="px-6 py-4 text-left font-label-md w-16">No</th>
                        <th class="px-6 py-4 text-left font-label-md">Nama &amp; Deskripsi Dokumen</th>
                        <th class="px-6 py-4 text-center font-label-md w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($documents as $index => $doc)
                    <tr class="hover:bg-peppermint/50 transition-colors">
                        <td class="px-6 py-6 text-on-surface-variant font-medium">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-start gap-4">
                                <i class="las la-file-alt text-3xl text-racing-green mt-1"></i>
                                <div>
                                    <h4 class="font-headline-md text-[18px] text-primary mb-1">{{ $doc->name }}</h4>
                                    <p class="text-on-surface-variant text-sm">
                                        {{ $doc->description ?? 'Tidak ada deskripsi tambahan untuk dokumen ini.' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <a
                                href="{{ asset('storage/' . $doc->file_path) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 bg-racing-green hover:bg-opacity-90 text-white px-5 py-2 rounded-lg transition-all font-label-md"
                            >
                                <span class="material-symbols-outlined text-[18px]">download</span>
                                Unduh
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-on-surface-variant">
                                <span class="material-symbols-outlined text-5xl opacity-30">folder_open</span>
                                <p class="font-label-md">Belum ada dokumen dalam kategori ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>



<script>
    document.querySelectorAll('a[href], button').forEach(el => {
        el.addEventListener('mousedown', function() { this.style.transform = 'scale(0.97)'; });
        el.addEventListener('mouseup',   function() { this.style.transform = 'scale(1)'; });
        el.addEventListener('mouseleave',function() { this.style.transform = 'scale(1)'; });
    });
</script>

</body>
</html>

@endsection



