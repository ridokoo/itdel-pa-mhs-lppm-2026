@extends('layouts.guest_master')
@section('title', 'Beranda - LPPM IT Del')

@section('content')

{{-- ===================== HERO SECTION ===================== --}}
<section class="relative min-h-[600px] flex items-center bg-itdel-deep-blue overflow-hidden">
    {{-- Background overlay --}}
    <div class="absolute inset-0 z-0 opacity-10">
        <img src="{{ asset('assets/itdel.png') }}" alt="" class="w-full h-full object-cover">
    </div>

    <div class="relative z-10 max-w-container-max mx-auto px-4 md:px-20 py-20 w-full">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            {{-- Kiri: Teks --}}
            <div>
                <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6">
                    Lembaga Penelitian dan Pengabdian kepada Masyarakat
                </h1>
                <p class="text-secondary-fixed text-base leading-relaxed mb-8 opacity-90">
                    LPPM merupakan unit kerja di perguruan tinggi yang bertanggung jawab dalam mengelola, mengoordinasikan, dan mengembangkan seluruh kegiatan penelitian serta pengabdian kepada masyarakat.
                </p>
                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="{{ route('guest.profile') }}"
                       class="bg-white text-itdel-deep-blue px-6 py-3 rounded-lg font-bold text-sm hover:bg-secondary-fixed transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                        Pelajari Lebih Lanjut
                    </a>
                    <a href="{{ route('contact.guest') }}"
                       class="border-2 border-white text-white px-6 py-3 rounded-lg font-bold text-sm hover:bg-white hover:text-itdel-deep-blue transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">contacts</span>
                        Hubungi Kami
                    </a>
                </div>

                {{-- Promo Links --}}
                <div class="flex gap-6">
                    <a href="{{ route('guest.news.index') }}"
                       class="flex flex-col items-center gap-2 bg-white/10 hover:bg-white/20 transition-all px-5 py-4 rounded-xl text-white text-center">
                        <span class="material-symbols-outlined text-3xl">newspaper</span>
                        <span class="text-xs font-semibold">Berita</span>
                    </a>
                    <a href="{{ route('guest.announcement.index') }}"
                       class="flex flex-col items-center gap-2 bg-white/10 hover:bg-white/20 transition-all px-5 py-4 rounded-xl text-white text-center">
                        <span class="material-symbols-outlined text-3xl">campaign</span>
                        <span class="text-xs font-semibold">Pengumuman</span>
                    </a>
                    <a href="{{ route('guest.documents.index') }}"
                       class="flex flex-col items-center gap-2 bg-white/10 hover:bg-white/20 transition-all px-5 py-4 rounded-xl text-white text-center">
                        <span class="material-symbols-outlined text-3xl">description</span>
                        <span class="text-xs font-semibold">Dokumen</span>
                    </a>
                </div>
            </div>

            {{-- Kanan: Gambar + Video --}}
            <div class="hidden md:flex justify-center items-center gap-4">
                <div class="relative">
                    <img src="{{ asset('assets/itdel.png') }}" alt="IT Del" class="rounded-2xl shadow-2xl w-72 object-cover">
                    <a href="https://www.youtube.com/watch?v=ihnUg0_eS8Q"
                       target="_blank"
                       class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-2xl group hover:bg-black/50 transition-all">
                        <span class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-itdel-blue text-4xl" style="font-variation-settings:'FILL' 1">play_arrow</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== STAT CARDS ===================== --}}
{{-- ===================== STAT CARDS ===================== --}}
<section class="bg-itdel-deep-blue pb-16">
    <div class="max-w-container-max mx-auto px-4 md:px-20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-itdel-blue text-center hover:-translate-y-1 transition-transform">
                <div class="text-itdel-deep-blue text-3xl font-bold mb-1">{{ $statResearch }}+</div>
                <div class="text-on-surface-variant text-xs font-semibold">Penelitian Aktif</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-itdel-blue text-center hover:-translate-y-1 transition-transform">
                <div class="text-itdel-deep-blue text-3xl font-bold mb-1">{{ $statCommunity }}+</div>
                <div class="text-on-surface-variant text-xs font-semibold">Pengabdian Masyarakat</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-itdel-blue text-center hover:-translate-y-1 transition-transform">
                <div class="text-itdel-deep-blue text-3xl font-bold mb-1">{{ $statLecturer }}+</div>
                <div class="text-on-surface-variant text-xs font-semibold">Dosen Terlibat</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-itdel-blue text-center hover:-translate-y-1 transition-transform">
                <div class="text-itdel-deep-blue text-3xl font-bold mb-1">{{ $statPublication }}+</div>
                <div class="text-on-surface-variant text-xs font-semibold">Luaran Publikasi</div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== ABOUT / VISI MISI ===================== --}}
<section class="py-20 bg-vista-white" id="about-section">
    <div class="max-w-container-max mx-auto px-4 md:px-20">
        <div class="text-center mb-12">
            <h2 class="text-primary text-3xl font-bold mb-3">Tentang LPPM IT Del</h2>
            <div class="w-16 h-1 bg-itdel-blue mx-auto"></div>
        </div>

        @forelse($visiMisi as $item)
        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            {{-- Gambar --}}
            <div class="relative">
                <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/img/about/ab-bg-1.jpg') }}"
                         alt="{{ $item->title }}"
                         class="w-full h-full object-cover">
                </div>
                {{-- Badge --}}
                <div class="absolute -bottom-4 -right-4 bg-itdel-blue text-white px-4 py-2 rounded-xl shadow-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">home_work</span>
                    <span class="text-sm font-bold">LPPM IT Del</span>
                </div>
            </div>
            {{-- Teks --}}
            <div>
                <h3 class="text-primary text-2xl font-bold mb-4">{{ $item->title }}</h3>
                <p class="text-on-surface-variant leading-relaxed mb-6">{{ $item->content }}</p>
                <a href="{{ route('guest.profile') }}"
                   class="inline-flex items-center gap-2 bg-itdel-blue text-white px-6 py-3 rounded-lg font-bold text-sm hover:bg-primary transition-all">
                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl mb-3 block">info</span>
            Data Visi & Misi belum tersedia.
        </div>
        @endforelse
    </div>
</section>

{{-- ===================== BERITA TERBARU ===================== --}}
<section class="py-20 bg-itdel-deep-blue">
    <div class="max-w-container-max mx-auto px-4 md:px-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-white text-3xl font-bold">Berita & Kegiatan Terbaru</h2>
                <div class="w-16 h-1 bg-secondary-fixed mt-4"></div>
                <p class="text-secondary-fixed opacity-80 text-sm mt-3">Ikuti perkembangan terbaru mengenai penelitian dan pengabdian masyarakat di LPPM IT Del.</p>
            </div>
            <a href="{{ route('guest.news.index') }}"
               class="text-secondary-fixed text-sm font-bold flex items-center gap-1 hover:text-white transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($berita as $item)
            <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="overflow-hidden aspect-[16/9]">
                    <img src="{{ $item->image ? asset('storage/'.$item->image) : asset('assets/img/service/service-10.jpg') }}"
                         alt="{{ $item->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <span class="bg-institutional-purple text-white text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wider mb-3 inline-block w-fit">Berita</span>
                    <h3 class="font-bold text-base text-primary leading-snug mb-2 line-clamp-2">
                        <a href="{{ route('guest.news.detail', $item->id) }}" class="hover:text-itdel-blue transition-colors">
                            {{ $item->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-on-surface-variant line-clamp-2 mb-4 flex-1">{{ strip_tags($item->content) }}</p>
                    <a href="{{ route('guest.news.detail', $item->id) }}"
                       class="text-itdel-blue text-xs font-bold flex items-center gap-1 hover:underline">
                        Baca Selengkapnya
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-12 text-white/60">
                <span class="material-symbols-outlined text-5xl mb-3 block">newspaper</span>
                Belum ada berita yang tersedia.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===================== PENGUMUMAN TERBARU ===================== --}}
<section class="py-20 bg-surface">
    <div class="max-w-container-max mx-auto px-4 md:px-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-primary text-3xl font-bold">Pengumuman Terbaru</h2>
                <div class="w-16 h-1 bg-itdel-blue mt-4"></div>
                <p class="text-on-surface-variant text-sm mt-3">Informasi resmi dan edaran terbaru dari LPPM IT Del.</p>
            </div>
            <a href="{{ route('guest.announcement.index') }}"
               class="text-itdel-blue text-sm font-bold flex items-center gap-1 hover:underline">
                Lihat Semua
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($pengumuman as $item)
            <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-outline-variant/30 flex flex-col group">
                <a href="{{ route('guest.announcement.detail', $item->id) }}" class="block overflow-hidden aspect-[16/9]">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-itdel-blue to-itdel-deep-blue flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-5xl opacity-40">campaign</span>
                        </div>
                    @endif
                </a>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-institutional-purple text-white text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wider">Pengumuman</span>
                    </div>
                    <h3 class="font-bold text-base text-primary leading-snug mb-2 line-clamp-2">
                        <a href="{{ route('guest.announcement.detail', $item->id) }}" class="hover:text-itdel-blue transition-colors">
                            {{ $item->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-on-surface-variant line-clamp-2 mb-4 flex-1">{{ strip_tags($item->content) }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-outline-variant/30">
                        <div class="flex items-center gap-1 text-xs text-outline">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                            <span>{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                        <a href="{{ route('guest.announcement.detail', $item->id) }}"
                           class="text-itdel-blue text-xs font-bold flex items-center gap-1 hover:underline">
                            Selengkapnya
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-12 text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl mb-3 block">campaign</span>
                Belum ada pengumuman terbaru saat ini.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===================== GALERI ===================== --}}
<section class="py-20 bg-vista-white">
    <div class="max-w-container-max mx-auto px-4 md:px-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-primary text-3xl font-bold">Galeri Kegiatan LPPM</h2>
                <div class="w-16 h-1 bg-itdel-blue mt-4"></div>
                <p class="text-on-surface-variant text-sm mt-3">Dokumentasi visual berbagai kegiatan penelitian dan pengabdian masyarakat IT Del.</p>
            </div>
            <a href="{{ route('guest.gallery.index') }}"
               class="text-itdel-blue text-sm font-bold flex items-center gap-1 hover:underline">
                Lihat Semua Album
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach(array_slice($galleries->toArray(), 0, 6) as $index => $gallery)
            <a href="{{ route('guest.gallery.detail', $gallery['id']) }}"
               class="group relative overflow-hidden rounded-xl shadow-md {{ $index === 0 ? 'md:col-span-2 md:row-span-2' : '' }} aspect-[4/3]">
                <img src="{{ asset('storage/'.$gallery['cover_image']) }}"
                     alt="{{ $gallery['title'] }}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-itdel-blue/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                    <div>
                        <h5 class="text-white font-bold text-sm">{{ $gallery['title'] }}</h5>
                        <span class="text-secondary-fixed text-xs flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span> Lihat Album
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== DOKUMEN & PANDUAN ===================== --}}
<section class="py-20 bg-surface">
    <div class="max-w-container-max mx-auto px-4 md:px-20">
        <div class="text-center mb-12">
            <h2 class="text-primary text-3xl font-bold mb-3">Dokumen & Panduan Terbaru</h2>
            <div class="w-16 h-1 bg-itdel-blue mx-auto mb-4"></div>
            <p class="text-on-surface-variant text-sm max-w-xl mx-auto">Unduh berkas administrasi, template proposal, dan buku panduan terbaru untuk mendukung kegiatan penelitian dan pengabdian Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($documents as $doc)
            <a href="{{ route('guest.documents.category', $doc->document_category_id) }}"
               class="bg-white border border-outline-variant/30 rounded-xl p-5 flex items-start gap-4 hover:shadow-md hover:border-itdel-blue transition-all group">
                <div class="w-12 h-12 bg-itdel-blue/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-itdel-blue transition-colors">
                    <span class="material-symbols-outlined text-itdel-blue group-hover:text-white transition-colors">description</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h5 class="font-bold text-sm text-primary line-clamp-2 mb-1 group-hover:text-itdel-blue transition-colors">
                        {{ $doc->name }}
                    </h5>
                    <p class="text-xs text-on-surface-variant line-clamp-2 mb-2">
                        {{ Str::limit($doc->description, 80) ?? 'Klik untuk melihat rincian dokumen.' }}
                    </p>
                    <span class="text-xs text-outline bg-surface-container px-2 py-1 rounded-full">
                        {{ $doc->category->name ?? '-' }}
                    </span>
                </div>
                <span class="material-symbols-outlined text-outline group-hover:text-itdel-blue transition-colors flex-shrink-0">download</span>
            </a>
            @empty
            <div class="col-span-3 text-center py-12 text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl mb-3 block">folder_open</span>
                Belum ada dokumen yang tersedia.
            </div>
            @endforelse
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('guest.documents.index') }}"
               class="inline-flex items-center gap-2 border-2 border-itdel-blue text-itdel-blue px-6 py-3 rounded-lg font-bold text-sm hover:bg-itdel-blue hover:text-white transition-all">
                Lihat Semua Dokumen
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

@endsection



