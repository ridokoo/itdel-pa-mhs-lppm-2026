@extends('layouts.guest_master')

@section('title', 'Rekapitulasi Data Penelitian | LPPM IT Del')

@section('content')
<!-- 1. Penyatuan Font dan Konfigurasi Tailwind agar sama dengan Galeri/Dokumen -->
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
                    },
                    "spacing": {
                        "container-max": "1320px",
                        "gutter": "1.5rem",
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
        .rekap-body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<div class="rekap-body">
    <!-- Breadcrumb & Header Rekapitulasi -->
    <section class="relative py-24 overflow-hidden bg-primary">
        <!-- Latar Belakang Gambar -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <img
                alt="background"
                class="w-full h-full object-cover"
                src="{{ asset('assets/img/rekapitulasi.jpg') }}"
            />
        </div>

        <!-- Ikon Dekorasi Besar di Belakang (Monitoring) -->
        <div class="absolute -right-20 -top-20 text-tertiary-fixed/10 select-none">
            <span class="material-symbols-outlined text-[300px]" style="font-size: 300px !important;">monitoring</span>
        </div>

        <!-- Konten Header -->
        <div class="relative px-gutter max-w-container-max mx-auto text-center md:text-left">
            <!-- Breadcrumb Navigasi -->
            <nav class="flex items-center gap-2 text-white/80 font-label-sm mb-4 text-[12px]">
                <a class="hover:text-white transition-colors" href="{{ route('welcome.guest') }}">Beranda</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-tertiary-fixed font-bold">Rekapitulasi Data</span>
            </nav>

            <!-- Judul Utama (Ukuran 48px - Identik Galeri) -->
            <h1 class="text-white font-display-lg text-[48px] font-bold leading-[1.2] tracking-[-0.02em]">
                Rekapitulasi Data Penelitian & Pengabdian
            </h1>

            <!-- Deskripsi (Ukuran 18px - Identik Galeri) -->
            <p class="text-white/70 max-w-2xl mt-4 font-body-lg text-[18px] leading-[1.6]">
                Visualisasi dan monitoring komprehensif seluruh output riset civitas akademika Institut Teknologi Del dalam satu dashboard terpadu.
            </p>
        </div>
    </section>

{{-- Filter Section (overlap card) --}}
<section class="max-w-7xl mx-auto px-6 -mt-8 relative z-20">
    <form method="GET" action="{{ route('guest.rekapitulasi.index') }}"
          class="bg-white border border-outline-variant shadow-md p-6 rounded-xl flex flex-wrap gap-4 items-end">

        {{-- Tahun Anggaran --}}
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-on-surface-variant mb-2 font-poppins">Tahun Anggaran</label>
            <div class="relative">
                <select name="tahun"
                        class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2.5 text-sm font-poppins appearance-none focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2.5 pointer-events-none text-outline text-base">expand_more</span>
            </div>
        </div>

        {{-- Fakultas / Departemen --}}
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-on-surface-variant mb-2 font-poppins">Fakultas / Departemen</label>
            <div class="relative">
                <select name="fakultas"
                        class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2.5 text-sm font-poppins appearance-none focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak }}" {{ request('fakultas') == $fak ? 'selected' : '' }}>{{ $fak }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2.5 pointer-events-none text-outline text-base">expand_more</span>
            </div>
        </div>

        {{-- Kategori Penelitian --}}
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-on-surface-variant mb-2 font-poppins">Kategori Penelitian</label>
            <div class="relative">
                <select name="kategori"
                        class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2.5 text-sm font-poppins appearance-none focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2.5 pointer-events-none text-outline text-base">expand_more</span>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-2">
            <button type="submit"
                    class="flex items-center gap-2 bg-primary text-on-primary px-6 py-2.5 rounded-lg text-sm font-semibold font-poppins hover:bg-opacity-90 transition-all">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                Terapkan Filter
            </button>
            @if(request()->hasAny(['tahun','fakultas','kategori','search']))
                <a href="{{ route('guest.rekapitulasi.index') }}"
                   class="flex items-center justify-center border border-outline text-on-surface-variant px-3 py-2.5 rounded-lg hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-base">close</span>
                </a>
            @endif
        </div>

    </form>
</section>

{{-- Stats Dashboard --}}
<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Total --}}
        <div class="bg-white p-6 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-secondary-container rounded-lg">
                    <span class="material-symbols-outlined text-on-secondary-container">inventory_2</span>
                </div>
            </div>
            <div class="text-xs font-semibold text-on-surface-variant font-poppins">Total Penelitian</div>
            <div class="text-3xl font-bold text-primary font-poppins mt-1">{{ number_format($stats['total']) }}</div>
        </div>

        {{-- Selesai --}}
        

        {{-- Tahun Aktif --}}
        <div class="bg-white p-6 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-primary-fixed rounded-lg">
                    <span class="material-symbols-outlined text-on-primary-fixed">calendar_today</span>
                </div>
            </div>
            <div class="text-xs font-semibold text-on-surface-variant font-poppins">Tahun Aktif</div>
            <div class="text-3xl font-bold text-primary font-poppins mt-1">{{ $stats['tahun_aktif'] ?? '-' }}</div>
        </div>

    </div>
</section>

{{-- Data Table --}}
<section class="max-w-7xl mx-auto px-6 pb-20">
    <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">

        {{-- Control Bar --}}
        <div class="p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface font-poppins">Daftar Proyek Penelitian</h2>
                <p class="text-xs text-outline mt-0.5 font-poppins">
                    Menampilkan {{ $penelitians->firstItem() }}–{{ $penelitians->lastItem() }} dari {{ number_format($penelitians->total()) }} data
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                {{-- Search --}}
                <form method="GET" action="{{ route('guest.rekapitulasi.index') }}" class="flex">
                    @foreach(request()->except('search','page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <div class="relative w-full md:w-64">
                        <span class="material-symbols-outlined absolute left-3 top-2 text-outline text-base">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul / peneliti..."
                               class="w-full bg-surface border border-outline-variant rounded-lg pl-10 pr-4 py-2 text-sm font-poppins focus:ring-2 focus:ring-primary-container outline-none transition-all">
                    </div>
                </form>



                {{-- Download Excel --}}
                <a href="{{ route('guest.rekapitulasi.export.excel', request()->query()) }}"
                   class="flex items-center gap-2 bg-surface border border-outline text-on-surface-variant px-4 py-2 rounded-lg text-sm font-semibold font-poppins hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined text-[18px]">table_view</span>
                    Excel
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-poppins">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-poppins">Judul Penelitian</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-poppins">Ketua Peneliti</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-poppins text-center">Tahun</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-poppins text-center">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider font-poppins text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($penelitians as $index => $item)
                        @php
                            $statusName  = strtolower($item->status->name ?? '');
                            $statusLabel = match(true) {
                                str_contains($statusName, 'approved') => ['label' => 'Disetujui',       'color' => 'text-green-700',  'dot' => 'bg-green-600',  'pulse' => false],
                                str_contains($statusName, 'selesai')  => ['label' => 'Selesai',         'color' => 'text-green-700',  'dot' => 'bg-green-600',  'pulse' => false],
                                str_contains($statusName, 'review')   => ['label' => 'Direview',        'color' => 'text-blue-600',   'dot' => 'bg-blue-500',   'pulse' => true],
                                str_contains($statusName, 'draft')    => ['label' => 'Draft',           'color' => 'text-on-surface-variant', 'dot' => 'bg-outline', 'pulse' => false],
                                str_contains($statusName, 'reject')   => ['label' => 'Ditolak',         'color' => 'text-red-600',    'dot' => 'bg-red-500',    'pulse' => false],
                                str_contains($statusName, 'berjalan') => ['label' => 'Sedang Berjalan', 'color' => 'text-amber-700',  'dot' => 'bg-amber-500',  'pulse' => true],
                                default                               => ['label' => ucfirst($statusName ?: '-'), 'color' => 'text-on-surface-variant', 'dot' => 'bg-outline', 'pulse' => false],
                            };
                        @endphp
                        <tr class="hover:bg-surface transition-colors cursor-pointer group">
                            <td class="px-6 py-5 text-sm text-on-surface-variant font-poppins">
                                {{ str_pad($penelitians->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-semibold text-primary leading-tight group-hover:underline font-poppins">
                                    {{ $item->title ?? '-' }}
                                </div>
                                @if($item->period)
                                    <div class="text-xs text-outline mt-1 italic">Periode: {{ $item->period->name ?? $item->period->year ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-sm text-on-surface font-poppins whitespace-nowrap">
                                {{ $item->leader->name ?? '-' }}
                            </td>
                            <td class="px-6 py-5 text-sm text-on-surface font-poppins text-center">
                                {{ $item->period->year ?? ($item->submitted_at ? \Carbon\Carbon::parse($item->submitted_at)->year : ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->year : '-')) }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($item->category)
                                    <span class="bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1 rounded-full text-xs font-bold font-poppins">
                                        {{ $item->category->name }}
                                    </span>
                                @else
                                    <span class="text-outline text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $statusLabel['dot'] }} {{ $statusLabel['pulse'] ? 'animate-pulse' : '' }}"></div>
                                    <span class="text-xs font-semibold font-poppins {{ $statusLabel['color'] }}">{{ $statusLabel['label'] }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant block mb-2">search_off</span>
                                <p class="text-sm text-on-surface-variant font-poppins">Tidak ada data penelitian yang sesuai filter.</p>
                                <a href="{{ route('guest.rekapitulasi.index') }}" class="text-sm text-primary font-semibold mt-1 inline-block hover:underline font-poppins">Reset filter</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($penelitians->hasPages())
            <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex flex-col md:flex-row justify-between items-center gap-4">
                <span class="text-xs font-semibold text-on-surface-variant font-poppins">
                    Menampilkan {{ $penelitians->firstItem() }}–{{ $penelitians->lastItem() }} dari {{ number_format($penelitians->total()) }} data
                </span>
                <div class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if($penelitians->onFirstPage())
                        <button disabled class="p-2 border border-outline-variant rounded-lg opacity-30 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $penelitians->previousPageUrl() }}" class="p-2 border border-outline-variant rounded-lg hover:bg-surface-variant transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach($penelitians->getUrlRange(max(1, $penelitians->currentPage() - 2), min($penelitians->lastPage(), $penelitians->currentPage() + 2)) as $page => $url)
                        @if($page == $penelitians->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center bg-primary text-on-primary rounded-lg text-sm font-bold font-poppins">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center hover:bg-surface-variant rounded-lg text-sm font-poppins transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($penelitians->currentPage() + 2 < $penelitians->lastPage())
                        <span class="px-2 text-on-surface-variant">...</span>
                        <a href="{{ $penelitians->url($penelitians->lastPage()) }}"
                           class="w-10 h-10 flex items-center justify-center hover:bg-surface-variant rounded-lg text-sm font-poppins transition-colors">
                            {{ $penelitians->lastPage() }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if($penelitians->hasMorePages())
                        <a href="{{ $penelitians->nextPageUrl() }}" class="p-2 border border-outline-variant rounded-lg hover:bg-surface-variant transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                    @else
                        <button disabled class="p-2 border border-outline-variant rounded-lg opacity-30 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

    </div>
</section>

@endsection



