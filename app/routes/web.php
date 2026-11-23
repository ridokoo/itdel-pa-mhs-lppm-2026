<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperatorController;

// Import Livewire Components
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\BerandaManager;
use App\Livewire\Admin\BeritaManager;
use App\Livewire\Admin\PengumumanManager;
use App\Livewire\Admin\KategoriDokumenManager;
use App\Livewire\Admin\DokumenManager;
use App\Livewire\Admin\KontakManager;
use App\Livewire\Admin\GalleryManager;
use App\Livewire\Ketua\FinalApprovalLivewire;
use App\Livewire\Master\ProposalStatusHistoryLivewire;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\LaporanAkhirController;





Route::middleware(['throttle:req-limit'])->group(function () {

    // ============================================================
    // 1. ZONA GUEST / LANDING (Tanpa Login)
    // ============================================================
    Route::get('/', [AppController::class, 'welcomeGuest'])->name('welcome.guest');

    Route::get('/about-lppm', [AppController::class, 'showGuestProfile'])
        ->name('guest.profile');

    Route::get('/about-lppm/detail/{id}', [AppController::class, 'showProfileDetail'])
        ->name('guest.profile.detail');

    Route::get('/news-lppm', [AppController::class, 'newsIndex'])
        ->name('guest.news.index');

    Route::get('/news/detail/{id}', [AppController::class, 'newsDetail'])
        ->name('guest.news.detail');

    Route::get('/announcements-lppm', [AppController::class, 'announcementIndex'])
        ->name('guest.announcement.index');

    Route::get('/announcement/detail/{id}', [AppController::class, 'announcementDetail'])
        ->name('guest.announcement.detail');

    Route::get('/documents-list', [AppController::class, 'documentIndex'])
        ->name('guest.documents.index');

    Route::get('/documents/category/{id}', [AppController::class, 'showDocumentsByCategory'])
        ->name('guest.documents.category');

    Route::get('/gallery-lppm', [AppController::class, 'galleryIndex'])
        ->name('guest.gallery.index');
    Route::get('/gallery-lppm/{id}', [AppController::class, 'galleryDetail'])
        ->name('guest.gallery.detail');

    Route::get('/contact', function () {

        return view('dashboard.guest.contact');
    })->name('contact.guest');
    Route::get('/rekapitulasi', [AppController::class, 'rekapitulasiIndex'])->name('guest.rekapitulasi.index');
    Route::get('/rekapitulasi/export/pdf', [AppController::class, 'rekapitulasiExportPdf'])->name('guest.rekapitulasi.export.pdf');
    Route::get('/rekapitulasi/export/excel', [AppController::class, 'rekapitulasiExportExcel'])->name('guest.rekapitulasi.export.excel');

    // ============================================================
    // 2. ZONA AUTHENTICATION & SSO
    // ============================================================
    Route::prefix('auth')->group(function () {

        Route::get('/login', [AuthController::class, 'login'])
            ->name('auth.login');

        Route::get('/register', [AuthController::class, 'register'])
            ->name('auth.register');

        Route::get('/logout', [AuthController::class, 'logout'])
            ->name('auth.logout');

        Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])
            ->name('auth.switch-role');

        // Fitur Tambahan Auth
        Route::get('/login/qrcode', [AuthController::class, 'loginQRCode'])
            ->name('auth.login.qrcode');

        Route::get('/totp', [AuthController::class, 'totp'])
            ->name('auth.totp');
    });

    Route::prefix('sso')->group(function () {

        Route::get('/callback', [AuthController::class, 'ssoCallback'])
            ->name('sso.callback');
    });

    Route::prefix('legal')->group(function () {

        Route::get('/privacy', [AppController::class, 'privacy'])
            ->name('legal.privacy');

        Route::get('/terms', [AppController::class, 'terms'])
            ->name('legal.terms');
    });

    // ============================================================
    // 3. ZONA TERPROTEKSI / APP (Wajib Login)
    // ============================================================
    Route::middleware('check.auth')->group(function () {

        // Fitur Ganti Peran (Mode BIMA)
        Route::get('/app/switch-role/{role}', [AuthController::class, 'switchRole'])
            ->name('auth.switch-role');

        Route::prefix('app')->group(function () {

            // ====================================================
            // UMUM
            // ====================================================
            Route::get('/beranda', AdminDashboard::class)
                ->name('index');

            Route::get('/profile', [AppController::class, 'profile'])
                ->name('app.profile');

            Route::get('/notification/{id}/open', [AppController::class, 'openNotification'])
                ->name('notification.open');

            Route::get('/reviewer', [AppController::class, 'reviewer'])
                ->name('reviewer');

            Route::get('/hak-akses', [AppController::class, 'hakAkses'])
                ->name('app.hak-akses');

            // ====================================================
            // REVIEWER
            // ====================================================
            Route::prefix('reviewer')->group(function () {

                Route::get('/dashboard', [AppController::class, 'reviewerDashboard'])
                    ->name('reviewer.dashboard');

                // ✅ Tambah ini
                Route::get('/laporan-kemajuan', [AppController::class, 'reviewerLaporanIndex'])
                    ->name('reviewer.laporan.index');

                Route::get('/laporan-kemajuan/{id}/review', [AppController::class, 'reviewerLaporanForm'])
                    ->name('reviewer.laporan.form');

                Route::get('/laporan-akhir', [AppController::class, 'reviewerLaporanAkhirIndex'])
                    ->name('reviewer.laporan-akhir.index');

                Route::get('/laporan-akhir/{id}/review', [AppController::class, 'reviewerLaporanAkhirForm'])
                    ->name('reviewer.laporan-akhir.form');
            });

            // ====================================================
            // ADMIN LPPM
            // ====================================================
            Route::prefix('admin')->group(function () {

                Route::get('/beranda-manager', BerandaManager::class)
                    ->name('admin.beranda');

                Route::get('/berita', BeritaManager::class)
                    ->name('admin.berita');

                Route::get('/pengumuman', PengumumanManager::class)
                    ->name('admin.pengumuman');

                Route::get('/kategori-dokumen', KategoriDokumenManager::class)
                    ->name('admin.kategori-dokumen');

                Route::get('/dokumen', DokumenManager::class)
                    ->name('admin.dokumen');

                Route::get('/kontak', KontakManager::class)
                    ->name('admin.kontak');

                Route::get('/gallery', GalleryManager::class)
                    ->name('admin.gallery');

                Route::get('/gallery/album/{id}', [AppController::class, 'galleryDetail'])
                    ->name('guest.gallery.detail');
            });

            // ====================================================
            // OPERATOR
            // ====================================================
            Route::prefix('operator')->group(function () {

                Route::get('/dashboard', [OperatorController::class, 'dashboard'])
                    ->name('operator.dashboard');

                Route::get('/proposal-status-history', [OperatorController::class, 'proposalStatusHistory'])
                    ->name('operator.proposal-status-history');

                Route::get('/fakultas', [OperatorController::class, 'fakultas'])
                    ->name('operator.fakultas');

                Route::get('/prodi', [OperatorController::class, 'prodi'])
                    ->name('operator.prodi');

                Route::get('/jabatan-fungsional', [OperatorController::class, 'jabatanFungsional'])
                    ->name('operator.jabatan-fungsional');

                Route::get('/kategori', [OperatorController::class, 'kategori'])
                    ->name('operator.kategori');

                Route::get('/kelompok-skema', [OperatorController::class, 'kelompokSkema'])
                    ->name('operator.kelompok-skema');

                Route::get('/tipe-skema', [OperatorController::class, 'schemeType'])
                    ->name('operator.tipe-skema');

                Route::get('/indeks', [OperatorController::class, 'indeks'])
                    ->name('operator.indeks');

                Route::get('/status-proposal', [OperatorController::class, 'proposalStatus'])
                    ->name('operator.status-proposal');

                Route::get('/status-member', [OperatorController::class, 'memberStatus'])
                    ->name('operator.member-status');

                Route::get('/status-reviewer', [OperatorController::class, 'reviewerStatus'])
                    ->name('operator.status-reviewer');

                Route::get('/reviewer', [OperatorController::class, 'reviewer'])
                    ->name('operator.reviewer');

                // Plotting Reviewer
                Route::get('/assign-reviewer', [OperatorController::class, 'penugasanReviewer'])
                    ->name('operator.assign-reviewer');

                Route::get('/scopes', [OperatorController::class, 'scopes'])
                    ->name('operator.scopes');

                Route::get('/monitoring-proposal', [OperatorController::class, 'monitoringUsulan'])
                    ->name('operator.monitoring-proposal');

                Route::get('/periode-proposal', [OperatorController::class, 'proposalPeriod'])
                    ->name('operator.proposalperiod');

                Route::get('/skema-keriteria', [OperatorController::class, 'kriteriaSkema'])
                    ->name('operator.skemakriteria');

                Route::get('/luaran-skema', [OperatorController::class, 'luaranSkema'])
                    ->name('operator.luaran-skema');

                Route::get('/kategori-biaya', [OperatorController::class, 'kategoriBiaya'])
                    ->name('operator.kategori-biaya');

                Route::get('/identitas-peneliti', [OperatorController::class, 'peneliti'])
                    ->name('operator.peneliti');

                Route::get('/output-target-type', [OperatorController::class, 'outputTargetType'])
                    ->name('operator.output-target-type');

                Route::get('/output-target-category', [OperatorController::class, 'outputTargetCategory'])
                    ->name('operator.output-target-category');

                Route::get('/output-target-detail', [OperatorController::class, 'outputTargetDetail'])
                    ->name('operator.output-target-detail');

                Route::get('/output-target-status', [OperatorController::class, 'outputTargetStatus'])
                    ->name('operator.output-target-status');

                Route::get('/target-tkt', [OperatorController::class, 'targetTkt'])
                    ->name('operator.target-tkt');
                    Route::get('/status-final-usulan', [OperatorController::class, 'statusFinalUsulan'])
    ->name('operator.status-final-usulan');

    });
            // ====================================================
            // KETUA
            // ====================================================
            Route::prefix('ketua')->group(function () {

                Route::get('/dashboard', [AppController::class, 'ketuaDashboard'])
                    ->name('ketua.dashboard');

                Route::get('/final-approval', [AppController::class, 'ketuaDashboard'])
                    ->name('ketua.final-approval');
                    Route::get('/status-final-usulan', \App\Livewire\Ketua\StatusFinalUsulanKetuaLivewire::class)
    ->name('ketua.status-final-usulan');
            });
            // Contoh di routes/web.php
            route::get('/app/beranda', [AppController::class, 'umumBeranda'])->name('index');


            // ====================================================
            // DOSEN
            // ====================================================
            Route::prefix('dosen')->group(function () {

                Route::get('/profil', [AppController::class, 'profilDosen'])
                    ->name('dosen.profil');

                Route::get('/usulan/{categoryId}', [AppController::class, 'indexUsulan'])
                    ->name('dosen.usulan.index');

                Route::get('/usulan/{categoryId}/form/{id_usulan?}', [AppController::class, 'formUsulan'])
                    ->name('dosen.usulan.form');

                // ── Laporan Kemajuan ─────────────────────────────────────
                Route::get('/laporan-kemajuan', [AppController::class, 'indexLaporanKemajuan'])
                    ->name('dosen.laporan-kemajuan.index');

                Route::get('/laporan-kemajuan/{proposal_id}/form/{id_laporan?}', [AppController::class, 'formLaporanKemajuan'])
                    ->name('dosen.laporan-kemajuan.form');

                // ── Laporan Akhir ────────────────────────────────────────
                Route::prefix('laporan-akhir')->name('dosen.laporan-akhir.')->group(function () {

                    // Daftar proposal eligible
                    Route::get('/', [LaporanAkhirController::class, 'index'])
                        ->name('index');

                    Route::get('/category/{categoryId}', [LaporanAkhirController::class, 'index'])
                        ->name('index.category');

                    // Form buat laporan akhir baru
                    Route::get('/create/{proposalId}', [LaporanAkhirController::class, 'form'])
                        ->name('create');

                    // Form edit laporan akhir
                    Route::get('/edit/{proposalId}/{idLaporan}', [LaporanAkhirController::class, 'form'])
                        ->name('edit');
                });

                // ── Lainnya ──────────────────────────────────────────────
                Route::get('/undangan-anggota', [AppController::class, 'indexUndangan'])
                    ->name('dosen.undangan');

                Route::get('/export/rab', function () {
                    $data = session('rab_export');
                    abort_if(!$data, 403);
                    session()->forget('rab_export');

                    $budgetItems       = $data['items'];
                    $expenseCategories = $data['categories'];
                    $totalRAB          = $data['total'];

                    $groupedItems = [];
                    foreach ($expenseCategories as $cat) {
                        $items    = collect($budgetItems)->where('expense_category_id', $cat['id'])->values();
                        $subTotal = $items->sum(fn($item) => (float) $item['total']);

                        $groupedItems[] = [
                            'category' => $cat,
                            'items'    => $items,
                            'subTotal' => $subTotal,
                        ];
                    }

                    $pdf = Pdf::loadView('exports.rab_pdf', [
                        'groupedItems'      => $groupedItems,
                        'expenseCategories' => $expenseCategories,
                        'totalRAB'          => $totalRAB,
                        'research'          => $data['research'] ?? [],
                        'generatedAt'       => now()->format('d F Y, H:i') . ' WIB',
                    ])->setPaper('a4', 'portrait');

                    return $pdf->download('RAB_' . now()->format('Ymd_His') . '.pdf');
                })->name('export.rab');
            });
        });
    });
});



