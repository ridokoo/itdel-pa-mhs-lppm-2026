@extends('layouts.guest_master')

@section('content')
<div class="bg-background text-on-surface font-body-md">

    {{-- Hero Header --}}
    <section class="relative h-64 md:h-80 flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            @if($profile->image)
                <img
                    alt="{{ $profile->title }}"
                    class="w-full h-full object-cover"
                    src="{{ asset('storage/' . $profile->image) }}"
                />
            @else
                <img
                    alt="Campus header"
                    class="w-full h-full object-cover"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfxc6-sSeLXOX7PsmBx5VN4GeESJUY6TZLg7DkdKFaIrppd43ikCFBIFlVOV_EexajD7LqpURpOCaDEL3E_ihzKj-KHTNzfXph_KG3OnTjIvwG_4DBqDnlbySuRQxkQTVHFYgi1DkdvE2IEJwHwvxIODrHvSNeVcm0D4rdcAKRuKUqhWz9dKGwG2MKmwu7oKZg4yrMQyTn6LSuRKc4HFRx32qgkBweHp72zXegD-hiSgqH9hdYArKYHHgmYtKL0AmMu3Ppgd8T8sK2"
                />
            @endif
        </div>
        <div class="absolute inset-0 racing-green-overlay z-10 leaf-pattern"></div>
        <div class="relative z-20 max-w-container-max mx-auto px-section-padding-mobile md:px-section-padding-desktop w-full text-white">
            <nav class="flex items-center gap-2 mb-4 font-label-sm text-label-sm opacity-80">
                <a class="hover:underline" href="{{ route('welcome.guest') }}">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a class="hover:underline" href="{{ route('guest.profile') ?? '#' }}">Tentang LPPM</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-secondary-fixed">{{ $profile->content_type ?? 'Detail' }}</span>
            </nav>
            <h1 class="font-display-lg text-display-lg leading-tight">{{ $profile->title }}</h1>
            @if($profile->content_type)
            <p class="font-body-md text-body-md mt-2 opacity-90">
                <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">label</span>
                    {{ ucfirst($profile->content_type) }}
                </span>
            </p>
            @endif
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-12 md:py-20 max-w-container-max mx-auto px-4 md:px-section-padding-desktop">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Konten Utama --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">

                    {{-- Gambar besar jika ada --}}
                    @if($profile->image)
                    <div class="h-72 md:h-96 overflow-hidden">
                        <img
                            src="{{ asset('storage/' . $profile->image) }}"
                            alt="{{ $profile->title }}"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    @endif

                    <div class="p-6 md:p-10">
                        {{-- Label kategori --}}
                        <div class="flex items-center gap-2 text-secondary mb-4">
                            <span class="material-symbols-outlined text-[20px]">info</span>
                            <span class="font-label-sm text-label-sm font-bold uppercase tracking-wider">
                                {{ $profile->content_type ?? 'Profil' }}
                            </span>
                        </div>

                        {{-- Judul --}}
                        <h2 class="font-headline-lg text-headline-lg mb-6 text-on-surface">
                            {{ $profile->title }}
                        </h2>

                        {{-- Divider --}}
                        <div class="h-1 w-16 bg-secondary rounded-full mb-6"></div>

                        {{-- Konten --}}
                        <div class="font-body-md text-body-md text-on-surface-variant leading-relaxed prose max-w-none">
                            {!! nl2br(e($profile->content)) !!}
                        </div>
                    </div>
                </div>

                {{-- Tombol Kembali --}}
                <div class="mt-8">
                    <a
                        href="{{ url()->previous() }}"
                        class="inline-flex items-center gap-2 font-label-md text-label-md text-secondary hover:underline group"
                    >
                        <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Info Card --}}
                <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-6">
                    <h3 class="font-title-md text-title-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[20px]">info</span>
                        Informasi
                    </h3>
                    <ul class="space-y-4 font-body-sm text-body-sm text-on-surface-variant">
                        @if($profile->content_type)
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">label</span>
                            <div>
                                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide mb-0.5">Kategori</p>
                                <p class="text-on-surface font-medium">{{ ucfirst($profile->content_type) }}</p>
                            </div>
                        </li>
                        @endif

                        @if($profile->created_at)
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">calendar_today</span>
                            <div>
                                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide mb-0.5">Tanggal Dibuat</p>
                                <p class="text-on-surface font-medium">
                                    {{ \Carbon\Carbon::parse($profile->created_at)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </li>
                        @endif

                        @if($profile->updated_at && $profile->updated_at != $profile->created_at)
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">update</span>
                            <div>
                                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide mb-0.5">Terakhir Diperbarui</p>
                                <p class="text-on-surface font-medium">
                                    {{ \Carbon\Carbon::parse($profile->updated_at)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </li>
                        @endif

                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">
                                {{ $profile->is_active ? 'check_circle' : 'cancel' }}
                            </span>
                            <div>
                                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wide mb-0.5">Status</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ $profile->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $profile->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Dibuat Oleh --}}
                @if($profile->creator)
                <div class="bg-white rounded-xl border border-outline-variant shadow-sm p-6">
                    <h3 class="font-title-md text-title-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[20px]">person</span>
                        Dibuat Oleh
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-secondary text-white flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($profile->creator->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface font-semibold">{{ $profile->creator->name ?? '-' }}</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $profile->creator->email ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

</div>
@endsection



