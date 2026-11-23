<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Profile;
use App\Models\News;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\DatabaseIndex;

// Tambahkan Helper jika Anda menggunakannya seperti di Profil
use App\Helper\WithComponentNameHelper;

class AdminDashboard extends Component
{
    use WithComponentNameHelper; // Agar konsisten dengan profil Anda

    public function render()
{
    // Mengambil satu periode yang sedang aktif
    // Sesuaikan 'status' dengan nama kolom status di tabel PROPOSAL_PERIODS Anda
    $activePeriod = \App\Models\ProposalPeriod::where('is_active', 'true')->first();

    $stats = [
        'total_beranda' => \App\Models\Profile::count(),
        'total_berita' => \App\Models\News::count(),
        'total_pengumuman' => \App\Models\Announcement::count(),
        'total_kategori' => \App\Models\DocumentCategory::count(),
        'total_dokumen' => \App\Models\Document::count(),
        'total_kontak' => \App\Models\Contact::count(),
        'total_galeri' => \App\Models\Gallery::count(),
        'activePeriod' => $activePeriod, // Kirim ke view
        'total_fakultas'   => \App\Models\Faculty::count(),
    'total_prodi'      => \App\Models\StudyProgram::count(),
    'total_indeks'     => \App\Models\DatabaseIndex::where('is_active', 1)->count(),
    ];



    return view('features.admin.admin-dashboard-livewire', $stats)
        ->layout('layouts.app');
}
}



