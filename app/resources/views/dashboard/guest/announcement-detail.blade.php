@extends('layouts.guest_master')

@push('styles')
<style>
    .leaf-pattern {
        background-image: url("https://www.transparenttextures.com/patterns/leaf.png");
        background-repeat: repeat;
        opacity: 0.15;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<main class="min-h-screen bg-background text-on-background">

    <!-- Breadcrumb Section -->
    <section class="bg-racing-green relative overflow-hidden py-12 px-6 md:px-20">
        <div class="absolute inset-0 leaf-pattern pointer-events-none"></div>
        <div class="max-w-[1320px] mx-auto relative z-10">
            <h1 class="text-5xl font-bold text-white mb-2">Detail Pengumuman</h1>
            <nav class="flex items-center gap-2 text-white/80 text-sm font-semibold">
                <span>Detail Informasi</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('guest.announcement.index') }}" class="text-white hover:underline">Pengumuman</a>
            </nav>
        </div>
    </section>

    <div class="max-w-[1320px] mx-auto px-6 md:px-20 -mt-10 mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Row 1 Left: Information Card -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-[20px] shadow-lg p-8 h-full flex flex-col justify-between border border-outline-variant">
                    <div>
                        <span class="inline-block bg-secondary-fixed text-primary px-3 py-1 rounded-full text-xs font-bold mb-6">
                            Informasi Pengumuman
                        </span>
                        <h2 class="text-2xl font-semibold text-primary mb-6 leading-tight">
                            {{ $announcement->title }}
                        </h2>

                        <div class="space-y-4 mb-8">
                            <!-- Start Date -->
                            <div class="flex items-center gap-4">
                                <div class="bg-secondary-fixed w-10 h-10 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-itdel-blue">calendar_today</span>
                                </div>
                                <div>
                                    <p class="text-xs text-outline uppercase tracking-wider font-medium">Berlaku Mulai</p>
                                    <p class="text-sm font-semibold text-on-background">{{ \Carbon\Carbon::parse($announcement->start_date)->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="flex items-center gap-4">
                                <div class="bg-error-container w-10 h-10 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-error">event_busy</span>
                                </div>
                                <div>
                                    <p class="text-xs text-outline uppercase tracking-wider font-medium">Sampai Dengan</p>
                                    <p class="text-sm font-semibold text-on-background">{{ \Carbon\Carbon::parse($announcement->end_date)->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Button -->
                    @if($announcement->attachment_file)
                        <a href="{{ asset('storage/'.$announcement->attachment_file) }}" target="_blank"
                           class="flex items-center justify-center gap-3 w-full py-4 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-itdel-blue transition-colors shadow-md active:scale-95">
                            <span class="material-symbols-outlined">download</span>
                            Download Lampiran (PDF)
                        </a>
                    @else
                        <div class="py-4 text-center text-outline italic border border-dashed border-outline-variant rounded-xl text-xs">
                            Tidak ada lampiran tersedia
                        </div>
                    @endif
                </div>
            </div>

            <!-- Row 1 Middle: Hero Image -->
            <div class="lg:col-span-4">
                <div class="rounded-[20px] overflow-hidden shadow-lg h-[600px] border border-outline-variant">
                    <img src="{{ $announcement->image ? asset('storage/'.$announcement->image) : asset('assets/img/project/project-1.jpg') }}"
                         alt="{{ $announcement->title }}"
                         class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Row 1 Right: Sidebar -->
            <aside class="lg:col-span-3 space-y-6">

                <!-- Pengumuman Terbaru -->
                <div class="bg-white border border-outline-variant rounded-[20px] p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-primary mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                        <span class="material-symbols-outlined">campaign</span>
                        Pengumuman Terbaru
                    </h3>
                    <div class="space-y-4">
                        @foreach($recentAnnouncements as $recent)
                        <a href="{{ route('guest.announcement.detail', $recent->id) }}" class="group block">
                            <p class="text-xs text-outline">{{ \Carbon\Carbon::parse($recent->created_at)->format('d M Y') }}</p>
                            <p class="text-sm font-semibold text-on-background group-hover:text-itdel-blue transition-colors line-clamp-2">
                                {{ $recent->title }}
                            </p>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Contact Widget -->
                <div class="bg-white border border-outline-variant rounded-[20px] overflow-hidden shadow-sm">
                    <div class="h-24 bg-racing-green/10 flex items-center justify-center relative">
                        <span class="material-symbols-outlined text-racing-green text-4xl opacity-20">hub</span>
                        <span class="absolute text-xs font-bold text-racing-green uppercase tracking-widest">Hubungi Kami</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-racing-green text-lg">location_on</span>
                            <p class="text-xs leading-relaxed">Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba, Sumatera Utara</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-racing-green text-lg">call</span>
                            <p class="text-sm font-semibold">+62 632 331234</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-racing-green text-lg">mail</span>
                            <p class="text-sm font-semibold">lppm@del.ac.id</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Content Section (Row 2) -->
        <section class="mt-12 bg-white rounded-[20px] border border-outline-variant p-8 md:p-12 shadow-sm">
            <h2 class="text-[2.2rem] font-bold text-primary mb-10 border-l-4 border-racing-green pl-6 leading-tight">
                {{ $announcement->title }}
            </h2>

            <div class="prose prose-lg max-w-none text-on-background leading-[2] text-[1.1rem] text-justify">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            <!-- Callout Quote -->
            <div class="bg-surface-container-low p-6 rounded-xl border border-outline-variant mt-10">
                <p class="italic text-on-surface-variant">
                    "Mendorong keunggulan riset dan pengabdian masyarakat untuk kemajuan bangsa melalui teknologi."
                </p>
            </div>
        </section>
    </div>
</main>
@endsection



