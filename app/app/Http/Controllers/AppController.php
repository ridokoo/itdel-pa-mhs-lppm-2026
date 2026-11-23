<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\News;
use App\Models\Gallery;
use App\Livewire\HakAkses\HakAksesLivewire;
use App\Livewire\Profile\ProfileLivewire;
use App\Livewire\Umum\BerandaLivewire;
use Illuminate\Http\Request;
use App\Livewire\Dosen\UsulanLivewire;
use App\Livewire\Dosen\UsulanFormLivewire;
use Illuminate\Support\Facades\DB;


class AppController extends Controller
{
    // Umum
    // ====================
    public function umumBeranda(Request $request)
    {
        $user = auth()->user();

        // Ambil role aktif dari session (seperti di sidebar)
        // Jika session kosong, ambil role pertama dari database
        $myRoles = $user->roles->pluck('name')->map(fn($n) => strtolower($n))->toArray();
        $activeRole = session('active_role', !empty($myRoles) ? $myRoles[0] : 'dosen');

        $categories = \App\Models\Category::orderBy('name')->get();
        $kategoriPenelitian = $categories->first(fn($c) => str_contains(strtolower($c->name), 'penelitian'));
        $kategoriPengabdian = $categories->first(fn($c) => str_contains(strtolower($c->name), 'pengabdian'));

        $data = [
            'auth'               => $request->auth,
            'title'              => 'Dashboard ' . ucwords($activeRole),
            'livewire'           => BerandaLivewire::getComponentName(),
            'kategoriPenelitian' => $kategoriPenelitian,
            'kategoriPengabdian' => $kategoriPengabdian,
        ];

        // ARAHKAN KE VIEW YANG SESUAI ROLE
        if ($activeRole == 'admin') {
            return view('app.admin.beranda', $data); // Buat file ini jika belum ada
        } elseif ($activeRole == 'operator') {
            return view('app.operator.beranda', $data); // Buat file ini jika belum ada
        } elseif ($activeRole == 'reviewer') {
            return view('app.reviewer.beranda', $data);
        } elseif ($activeRole == 'ketua lppm') {
            return view('app.ketua.beranda', $data);
        }

        // Default ke Beranda Dosen
        return view('app.dosen.beranda', $data);
    }
    public function welcomeGuest()
    {
        // 1. Ambil data Hero (Greeting)
        $hero = Profile::where('content_type', 'greeting')->where('status', 'published')->first();

        // 2. Ambil data Visi & Misi secara spesifik
        $visiMisi = Profile::where('content_type', 'vision_mission')
            ->where('status', 'published')
            ->orderBy('display_order', 'asc')
            ->get();

        // 3. Ambil semua profil untuk slider lainnya
        $allProfiles = Profile::where('status', 'published')
            ->orderBy('display_order', 'asc')
            ->get();

        // 4. Ambil data Berita - UBAH MENJADI take(3)
        $berita = News::where('status', 'published')
            ->orderBy('event_date', 'desc')
            ->take(3)
            ->get();

        // 5. Pengumuman
        $pengumuman = \App\Models\Announcement::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // 6. Dokumen & Panduan
        $documents = \App\Models\Document::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        // 7. Galeri
        $galleries = \App\Models\Gallery::where('status', 'published')
            ->orderBy('event_date', 'desc') // Urutkan berdasarkan tanggal kegiatan
            ->latest()                      // Jika tanggal sama, urutkan berdasarkan upload terbaru
            ->take(6)
            ->get();

        // Statistik Dinamis
        $statResearch = \App\Models\Proposal::whereHas('category', function ($q) {
            $q->whereLike('name', '%penelitian%');
        })->where('is_active', true)->count();

        $statCommunity = \App\Models\Proposal::whereHas('category', function ($q) {
            $q->whereLike('name', '%pengabdian%');
        })->where('is_active', true)->count();

        $statLecturer = \App\Models\ProposalMember::distinct('user_id')->count('user_id');

        $statPublication = \App\Models\ProposalOutputTarget::count();

        return view('dashboard.guest.welcome', compact(
            'hero',
            'visiMisi',
            'allProfiles',
            'berita',
            'pengumuman',
            'documents',
            'galleries',
            'statResearch',
            'statCommunity',
            'statLecturer',
            'statPublication' // ← tambahkan ini
        ));

        // Pastikan logika deteksi orientasi tetap ada di bawahnya agar gambar tidak portrait terus
        foreach ($galleries as $g) {
            $path = public_path('storage/' . $g->cover_image);
            if (file_exists($path)) {
                list($width, $height) = getimagesize($path);
                $g->orientation = ($width > $height) ? 'landscape' : 'portrait';
            }
        }

        return view('dashboard.guest.welcome', compact('hero', 'visiMisi', 'allProfiles', 'berita', 'pengumuman', 'documents', 'galleries'));
    }

    // Visi Misi
    public function showGuestProfile()
    {
        // Mengambil data (Profil, Visi Misi, Sambutan, Deskripsi Sistem)
        $allProfiles = \App\Models\Profile::where('status', 'published')
            ->orderBy('display_order', 'asc')
            ->get();

        return view('dashboard.guest.profile', compact('allProfiles'));
    }

    // Detail Visi Misi
    public function showProfileDetail($id)
    {
        $profile = Profile::with('creator')->findOrFail($id);

        $news = \App\Models\News::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard.guest.profile-detail', compact('profile', 'news')); // ← 'data' → 'profile'
    }
    // Berita
    public function newsIndex()
    {
        // UBAH ->get() MENJADI ->paginate(6)
        $news = \App\Models\News::where('status', 'published')
            ->orderBy('event_date', 'desc')
            ->paginate(6); // Menampilkan 6 berita per halaman

        return view('dashboard.guest.news', compact('news'));
    }

    // Detail Berita
    public function newsDetail($id)
    {
        // Ambil detail berita berdasarkan ID
        $item = \App\Models\News::findOrFail($id);

        // Ambil 3 berita lainnya untuk bagian Promo (Other News)
        $otherNews = \App\Models\News::where('id', '!=', $id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard.guest.news-detail', compact('item', 'otherNews'));
    }

    // Pengumuman
    public function announcementIndex(Request $request)
    {
        $query = \App\Models\Announcement::where('status', 'published')
            ->where('is_active', true); // ✅ FIX: tambah filter is_active

        // ✅ FIX: Search fungsional
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('content', 'like', '%' . $request->q . '%');
            });
        }

        // ✅ FIX: Sort fungsional
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('start_date', 'asc');
        } else {
            $query->orderBy('start_date', 'desc'); // default: terbaru
        }

        $announcements = $query->paginate(6)->withQueryString(); // withQueryString() agar pagination bawa parameter search/sort

        return view('dashboard.guest.announcement', compact('announcements'));
    }

    // Pengumuman Detail
    public function announcementDetail($id)
    {
        // 1. Ambil detail pengumuman berdasarkan ID
        $announcement = \App\Models\Announcement::findOrFail($id);

        // 2. Ambil 5 pengumuman terbaru lainnya untuk sidebar
        $recentAnnouncements = \App\Models\Announcement::where('status', 'published')
            ->where('id', '!=', $id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.guest.announcement-detail', compact('announcement', 'recentAnnouncements'));
    }

    // Dokumen & Panduan Utama
    public function documentIndex(Request $request)
    {
        $query = \App\Models\Document::where('status', 'published');

        // Jika ada filter kategori dari navbar
        if ($request->has('category')) {
            $query->where('document_category_id', $request->category);
            $category = \App\Models\DocumentCategory::find($request->category);
        } else {
            // Jika tidak ada filter, buat objek dummy agar blade tidak error
            $category = (object) ['name' => 'Semua Dokumen', 'id' => null];
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = \App\Models\DocumentCategory::all();

        // GANTI DISINI: Pastikan memanggil 'document-category'
        return view('dashboard.guest.document-category', compact('documents', 'categories', 'category'));
    }

    // Dokumen Per Kategori (URL Spesifik)
    public function showDocumentsByCategory($id)
    {
        $category = \App\Models\DocumentCategory::findOrFail($id);
        $documents = \App\Models\Document::where('document_category_id', $id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = \App\Models\DocumentCategory::all(); // Tambahkan ini agar navbar kategori tetap muncul

        return view('dashboard.guest.document-category', compact('category', 'documents', 'categories'));
    }

    // ============================================================
    // REKAPITULASI DATA (Guest)
    // ============================================================

    public function rekapitulasiIndex(Request $request)
    {
        $query = \App\Models\Proposal::with(['leader', 'category', 'status', 'period'])
            ->where('is_active', true);

        if ($request->filled('tahun')) {
            $query->whereHas('period', fn($q) => $q->where('year', $request->tahun));
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('leader', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $penelitians = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $tahunList = \App\Models\Proposal::where('is_active', true)
            ->whereNotNull('submitted_at')
            ->selectRaw('YEAR(submitted_at) AS tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->unique()
            ->values();
        $kategoriList = \App\Models\Category::orderBy('name')->get();

        $baseQuery = \App\Models\Proposal::where('is_active', true);
        $total     = (clone $baseQuery)->count();
        $selesai   = (clone $baseQuery)->whereHas('status', fn($q) => $q->whereLike('name', '%selesai%'))->count();
        $berjalan  = (clone $baseQuery)->whereHas('status', fn($q) => $q->whereLike('name', '%berjalan%'))->count();
        $tahunAktif = \App\Models\ProposalPeriod::orderByDesc('year')->value('year');

        return view('dashboard.guest.rekapitulasi', [
            'penelitians'  => $penelitians,
            'tahunList'    => $tahunList,
            'fakultasList' => \App\Models\Faculty::where('is_active', true)->orderBy('name')->pluck('name'),
            // 'fakultasList' => collect([]), // kosongkan dulu, isi jika ada kolom department di users
            'kategoriList' => $kategoriList,
            'stats' => [
                'total'       => $total,
                'selesai'     => $selesai,
                'berjalan'    => $berjalan,
                'tahun_aktif' => $tahunAktif,
            ],
        ]);
    }

    public function rekapitulasiExportPdf(Request $request)
    {
        $proposals = $this->rekapitulasiGetQuery($request)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.guest.rekapitulasi-pdf', [
            'proposals' => $proposals,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekapitulasi-penelitian.pdf');
    }

    public function rekapitulasiExportExcel(Request $request)
    {
        $proposals = $this->rekapitulasiGetQuery($request)->get();

        $filename = 'rekapitulasi-penelitian.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($proposals) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Judul Penelitian', 'Ketua Peneliti', 'Tahun', 'Kategori', 'Status']);
            foreach ($proposals as $i => $p) {
                fputcsv($handle, [
                    $i + 1,
                    $p->title,
                    $p->leader->name ?? '-',
                    $p->period->year ?? '-',
                    $p->category->name ?? '-',
                    $p->status->name ?? '-',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function rekapitulasiGetQuery(Request $request)
    {
        $query = \App\Models\Proposal::with(['leader', 'category', 'status', 'period'])
            ->where('is_active', true);

        if ($request->filled('tahun')) {
            $query->whereRaw('YEAR(submitted_at) = ?', [$request->tahun]);
        }
        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('leader', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        return $query->orderByDesc('created_at');
    }

    // Album
    public function galleryIndex()
    {
        $allGalleries = \App\Models\Gallery::where('status', 'published')
            ->orderBy('event_date', 'desc') // HARUS SAMA DENGAN DI ATAS
            ->latest()                      // HARUS SAMA DENGAN DI ATAS
            ->paginate(12);                 // Ambil 12 agar ada 2 baris mosaik (6+6)

        // Jangan lupa tambahkan deteksi orientasi di sini juga agar di halaman album gambarnya pas
        foreach ($allGalleries as $g) {
            $path = public_path('storage/' . $g->cover_image);
            if (file_exists($path)) {
                list($width, $height) = getimagesize($path);
                $g->orientation = ($width > $height) ? 'landscape' : 'portrait';
            }
        }

        return view('dashboard.guest.gallery-all', compact('allGalleries'));
    }

    // Mengambil data album beserta semua fotonya (items)
    public function galleryDetail($id)
    {
        // Ambil data album beserta item foto di dalamnya
        $gallery = \App\Models\Gallery::with('items')->findOrFail($id);

        return view('dashboard.guest.gallery-detail', compact('gallery'));
    }

    // Profile
    // ====================
    public function profile(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Profile',
            'livewire' => ProfileLivewire::getComponentName(),
        ];

        return view('app.umum.profil', $data);
    }

    // Hak Akses
    // ====================
    public function hakAkses(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Profile',
            'livewire' => HakAksesLivewire::getComponentName(),
        ];

        return view('pages.app', $data);
    }

    // Legal
    // ====================
    public function privacy(Request $request)
    {
        $data = [
            'auth' => $request->auth,
        ];

        return view('features.legal.privacy-page', $data);
    }

    public function terms(Request $request)
    {
        $data = [
            'auth' => $request->auth,
        ];

        return view('features.legal.terms-page', $data);
    }

    public function reviewer()
    {
        // Ini akan memanggil file resources/views/reviewer.blade.php
        return view('app.dosen.reviewer');
    }

    public function reviewerLaporanIndex(Request $request)
    {
        return view('app.reviewer.laporan-kemajuan.index', [  // ← ubah ini
            'auth'    => $request->auth,
            'title'   => 'Review Laporan Kemajuan',
            'livewire' => \App\Livewire\Reviewer\LaporanReviewIndexLivewire::getComponentName(),
        ]);
    }

    public function reviewerLaporanForm(Request $request, $id)
    {
        return view('app.reviewer.laporan-kemajuan.form', [  // ← ubah ini
            'auth'       => $request->auth,
            'title'      => 'Form Review Laporan',
            'id_laporan' => $id,
            'livewire'   => \App\Livewire\Reviewer\LaporanReviewFormLivewire::getComponentName(),
        ]);
    }

    public function profilDosen(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Lengkapi Profil Dosen',
            'livewire' => \App\Livewire\Dosen\ProfileManagerLivewire::getComponentName(),
        ];
        return view('app.dosen.profil', $data);
    }

    public function indexUsulan(Request $request, $categoryId)
    {
        // Tentukan judul berdasarkan data asli database Anda
        // $title = ($categoryId == 4) ? 'Daftar Usulan Penelitian' : 'Daftar Usulan Pengabdian (PKM)';
        $category = \App\Models\Category::findOrFail($categoryId);
        $title = $category->name;


        return view('app.dosen.usulan', [
            'auth' => $request->auth,
            'title' => 'Daftar Usulan ' . $title,
            'categoryId' => $categoryId,
            'livewire' => \App\Livewire\Dosen\UsulanLivewire::getComponentName()
        ]);
    }

    public function formUsulan(Request $request, $categoryId, $id = null)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Form Usulan',
            'categoryId' => $categoryId,
            'id_usulan' => $id, // ID ini harus dikirim!
            'livewire' => \App\Livewire\Dosen\UsulanFormLivewire::getComponentName(),
        ];

        if (!$id) {
            $now = now()->format('Y-m-d');
            $check = \App\Models\ProposalPeriod::where('is_active', true)
                ->whereDate('start_date', '<=', $now)
                ->whereDate('end_date', '>=', $now)
                ->exists();

            if (!$check) {
                // Lempar balik ke daftar dengan pesan error
                return redirect()->route('dosen.usulan.index', ['categoryId' => $categoryId])
                    ->with('error', 'Maaf, periode pendaftaran usulan baru sudah ditutup.');
            }
        }

        return view('app.dosen.usulan.form', $data);
    }

    public function openNotification(Request $request, $id)
    {
        $notifService  = app(\App\Services\NotificationService::class);
        $notifications = collect($notifService->getNotifications(auth()->id()) ?? []);

        $notification = $notifications->firstWhere('id', $id);

        abort_if(!$notification, 404);

        // Mark semua sebagai read (atau bisa tambah markOneRead jika service mendukung)
        $notifService->markRead(auth()->id());

        // Ambil URL tujuan dari data notifikasi
        $data = isset($notification['data']) && is_array($notification['data'])
            ? $notification['data']
            : (array) ($notification['data'] ?? []);

        $url = $data['url'] ?? $notification['url'] ?? null;

        if (!$url || $url === '#') {
            return redirect()->route('index');
        }

        return redirect()->to($url);
    }

    public function indexUndangan(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Undangan Anggota Usulan',
            // Kita akan buat class Livewire ini di langkah berikutnya
            'livewire' => \App\Livewire\Dosen\MemberInvitationLivewire::getComponentName(),
        ];

        return view('app.dosen.usulan.index', $data); // Gunakan wadah index yang sudah ada
    }

    public function reviewerDashboard(Request $request)
    {
        return view('app.reviewer.index', [
            'auth' => $request->auth,
            'title' => 'Dashboard Reviewer',
            'livewire' => \App\Livewire\Reviewer\ReviewerDashboardLivewire::getComponentName(),
        ]);
    }

    public function ketuaDashboard(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Persetujuan Final Ketua LPPM',
            'livewire' => \App\Livewire\Ketua\FinalApprovalLivewire::getComponentName(),
        ];
        return view('app.ketua.index', $data); // Wadah khusus Ketua
    }

    private function getKategori()
    {
        return \App\Models\Category::orderBy('name')->get()->keyBy(function ($c) {
            return strtolower(str_replace(' ', '_', $c->name));
        });
    }

    public function indexLaporanKemajuan(Request $request)
    {
        $categoryId = $request->query('categoryId');

        return view('app.dosen.laporan.index', [
            'auth'       => $request->auth,
            'title'      => 'Laporan Kemajuan',
            'categoryId' => $categoryId,
            'livewire'   => 'dosen.laporan-kemajuan-index-livewire', // ← hardcode
        ]);
    }

    public function formLaporanKemajuan(Request $request, $proposal_id, $id_laporan = null)
    {
        return view('app.dosen.laporan.form', [
            'auth'        => $request->auth,
            'title'       => 'Form Laporan Kemajuan',
            'proposal_id' => $proposal_id,
            'id_laporan'  => $id_laporan,
            'livewire'    => 'dosen.laporan-kemajuan-form-livewire', // ← hardcode
        ]);
    }

    public function reviewerLaporanAkhirIndex(Request $request)
    {
        return view('app.reviewer.laporan-kemajuan.index', [
            'auth'     => $request->auth,
            'title'    => 'Review Laporan Akhir',
            'livewire' => \App\Livewire\Reviewer\LaporanAkhirReviewIndexLivewire::getComponentName(),
        ]);
    }

    public function reviewerLaporanAkhirForm(Request $request, $id)
    {
        return view('app.reviewer.laporan-kemajuan.form', [
            'auth'       => $request->auth,
            'title'      => 'Form Review Laporan Akhir',
            'id_laporan' => $id,
            'livewire'   => \App\Livewire\Reviewer\LaporanAkhirReviewFormLivewire::getComponentName(),
        ]);
    }
}
