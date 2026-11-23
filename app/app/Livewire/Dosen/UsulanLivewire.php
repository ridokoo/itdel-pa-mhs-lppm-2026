<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Proposal;
use App\Models\User;
use App\Models\ProposalPeriod;
use App\Helper\WithComponentNameHelper;
use Livewire\Attributes\Url; // Tambahkan ini jika ingin filter tahun tersimpan di URL


class UsulanLivewire extends Component
{
    use WithComponentNameHelper;
    public $isPeriodOpen = false;
    public $activePeriodInfo = "";
    public $categoryId;
    public $localUser;
    public $viewedProposal;
    public $commentType = 'lppm'; // Tambahan untuk membedakan tipe komentar
    public $selected_id; // Tambahkan ini untuk menampung ID saat hapus
    #[Url]
    public $eligibilityMessages = [], $isEligible = true;
    public $filterYear;
    public $userProfile;
    public $eligibleSchemes = [];

    public function mount($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->localUser = \App\Models\User::where('email', request()->auth->email)->first();

        // 1. Ambil data profil lokal
        $this->userProfile = \App\Models\UserProfile::with(['studyProgram', 'functionalPosition'])
            ->where('user_id', $this->localUser->id)
            ->first();

        // 2. Hitung eligibilitas
        $this->calculateEligibility();

        // 3. Cek apakah periode sedang dibuka
        $now = now()->format('Y-m-d');
        $period = \App\Models\ProposalPeriod::where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->first();

        if ($period) {
            $this->isPeriodOpen = true;
            $this->activePeriodInfo = "Periode dibuka: " . $period->name;
        } else {
            $this->isPeriodOpen = false;
            $this->activePeriodInfo = "Pendaftaran usulan saat ini sedang ditutup atau sudah berakhir.";
        }

        // 4. Set default filter tahun
        if (!$this->filterYear) {
            $this->filterYear = date('Y');
        }
    }

    public function edit($id)
    {
        $proposal = \App\Models\Proposal::findOrFail($id);

        if ($proposal->leader_id !== $this->localUser->id) {
            $this->dispatch('showErrorAlert', 'Hanya ketua pengusul yang dapat mengedit usulan ini.');
            return;
        }

        $statusName = strtolower($proposal->status?->name ?? '');

        // Hanya draft dan revisi yang boleh diedit
        // Setelah revisi di-submit → status jadi 'diajukan' → tidak bisa edit lagi
        $isEditable = str_contains($statusName, 'draft') ||
            str_contains($statusName, 'revisi') ||
            str_contains($statusName, 'revision');

        if (!$isEditable) {
            $this->dispatch('showErrorAlert', 'Usulan tidak dapat diedit. Status saat ini: ' . $proposal->status?->name . '. Hubungi LPPM jika ada kendala.');
            return;
        }

        return redirect()->route('dosen.usulan.form', [
            'categoryId' => $this->categoryId,
            'id_usulan' => $id
        ]);
    }

    public function deleteConfirm($id)
    {
        $proposal = \App\Models\Proposal::findOrFail($id);

        // Hanya ketua yang boleh menghapus
        if ($proposal->leader_id !== $this->localUser->id) {
            $this->dispatch('showErrorAlert', 'Hanya ketua pengusul yang dapat menghapus usulan ini.');
            return;
        }

        // Tidak bisa hapus jika sedang dalam proses review/diajukan
        $statusName = strtolower($proposal->status?->name ?? '');
        $nonDeletableStatuses = ['diajukan', 'aju', 'review', 'approved', 'setuju', 'revisi', 'revision'];

        foreach ($nonDeletableStatuses as $blocked) {
            if (str_contains($statusName, $blocked)) {
                $this->dispatch('showErrorAlert', 'Usulan tidak dapat dihapus karena sedang dalam proses ' . $proposal->status->name . '.');
                return;
            }
        }

        $this->selected_id = $id;
        $this->dispatch('showModal', id: '#modal_hapus_usulan');
    }

    public function destroy()
    {
        if (!$this->selected_id) return;

        $proposal = \App\Models\Proposal::findOrFail($this->selected_id);

        // Double-check validasi di backend (antisipasi manipulasi)
        if ($proposal->leader_id !== $this->localUser->id) {
            $this->dispatch('showErrorAlert', 'Akses ditolak.');
            return;
        }

        $statusName = strtolower($proposal->status?->name ?? '');
        $nonDeletableStatuses = ['diajukan', 'aju', 'review', 'approved', 'setuju', 'revisi', 'revision'];

        foreach ($nonDeletableStatuses as $blocked) {
            if (str_contains($statusName, $blocked)) {
                $this->dispatch('showErrorAlert', 'Usulan tidak dapat dihapus.');
                return;
            }
        }

        \App\Models\BudgetItem::where('proposal_id', $this->selected_id)->delete();
        \App\Models\ProposalMember::where('proposal_id', $this->selected_id)->delete();
        \App\Models\ProposalPartner::where('proposal_id', $this->selected_id)->delete();
        \App\Models\ProposalOutputTarget::where('proposal_id', $this->selected_id)->delete();

        if ($proposal->proposal_file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($proposal->proposal_file);
        }

        $proposal->delete();

        $this->dispatch('hideModal', id: '#modal_hapus_usulan');
        $this->dispatch('showSuccessAlert', 'Usulan berhasil dihapus.');
        $this->reset('selected_id');
    }

    public function render()
    {
        // 1. Ambil data kategori berdasarkan ID yang dikirim dari URL/Navbar
        // Jika ID berubah di DB, sistem tetap tahu namanya karena kita mencari berdasarkan ID yang aktif
        $category = \App\Models\Category::find($this->categoryId);

        // Fallback: Jika kategori tidak ditemukan, beri judul default
        $titleLabel = $category ? $category->name : 'Daftar Usulan';

        // 2. Query Utama (Tetap dinamis menggunakan category_id yang sedang aktif)
        $query = Proposal::with(['status', 'schemeType', 'period', 'leader', 'reviewers.reviewer'])
            ->where('category_id', $this->categoryId)
            ->where(function ($q) {
                $q->where('leader_id', $this->localUser->id)
                    ->orWhereHas('proposalMembers', function ($memberQuery) {
                        $memberQuery->where('user_id', $this->localUser->id);
                    });
            });

        // 3. Filter Tahun (Gunakan nama status, bukan ID status agar lebih aman)
        if ($this->filterYear && $this->filterYear != 'all') {
            $query->where(function ($q) {
                $q->whereHas('period', function ($p) {
                    $p->where('year', $this->filterYear);
                });

                // Jika filter tahun sekarang, tampilkan juga yang belum ada periode (Draft baru)
                if ($this->filterYear == date('Y')) {
                    $q->orWhereNull('period_id')
                        ->orWhereHas('status', function ($s) {
                            $s->whereLike('name', '%draft%');
                        });
                }
            });
        }

        return view('features.dosen.usulan.index-livewire', [
            'myProposals' => $query->orderBy('updated_at', 'desc')->get(),
            'title' => $titleLabel, // Tulisan "Program Penelitian" atau "Pengabdian" diambil langsung dari DB
            'availableYears' => range(date('Y') + 1, date('Y') - 5)
        ]);
    }

    // Fungsi untuk melihat komentar
    public function showComment($id, $type = 'lppm')
    {
        // Ambil data usulan beserta komentar (decision_note dan reviewer_note)
        $this->viewedProposal = \App\Models\Proposal::with('reviewers.reviewer')->findOrFail($id);
        $this->commentType = $type;

        // Pemicu modal
        $this->dispatch('showModal', id: '#modal_view_comment');
    }

    public function calculateEligibility()
    {
        if (!$this->userProfile) return;

        // 1. Tentukan Ranking Jabatan Fungsional (Sesuaikan ID dengan Database Anda)
        // Anggap saja ID Jabfung Anda: 1=Asisten Ahli, 2=Lektor, 3=Lektor Kepala, 4=Guru Besar
        $jabfungRank = [
            'Lektor' => 1, // Ganti 'AA' dengan ID asli Asisten Ahli di DB Anda
            'Lektor Kepala'  => 2, // Ganti 'L' dengan ID asli Lektor
            'Asisten Ahli' => 3,
            'Guru Besar (Profesor)' => 4  // Tenaga Pengajar / Belum ada Jabfung
        ];

        // Ambil semua skema yang sesuai dengan Kategori (Penelitian/Pengabdian)
        $allSchemes = \App\Models\SchemeType::whereHas('schemeGroup', function ($q) {
            $q->where('category_id', $this->categoryId); // Pastikan hanya penelitian saja atau pengabdian saja
        })->get();

        $this->eligibleSchemes = [];

        // Deteksi Pendidikan
        $isS3 = str_contains(strtoupper($this->localUser->name), 'DR.') || str_contains(strtoupper($this->localUser->name), 'PH.D');

        // Ambil Rank User saat ini
        $userRank = $jabfungRank[$this->userProfile->functional_position_id] ?? 0;

        foreach ($allSchemes as $scheme) {
            $rule = $scheme->criteria->first();

            // Jika skema tidak punya aturan (criteria), anggap boleh diambil semua orang
            if (!$rule) {
                $this->eligibleSchemes[] = $scheme->name;
                continue;
            }

            // A. Cek Pendidikan
            $passEdu = ($isS3 && $rule->allow_s3) || (!$isS3 && $rule->allow_s2);

            // B. Cek Jabatan Fungsional (User Rank harus >= Syarat Minimal Rank)
            $requiredRank = $jabfungRank[$rule->min_functional_position_id] ?? 0;
            $passJabfung = ($userRank >= $requiredRank);

            // Jika keduanya terpenuhi, masukkan ke daftar rekomendasi
            if ($passEdu && $passJabfung) {
                $this->eligibleSchemes[] = $scheme->name;
            }
        }
    }

    public function checkEligibility($schemeId)
    {
        $this->eligibilityMessages = [];
        $this->isEligible = true;

        if (!$schemeId) return;

        // 1. Ambil baris kriteria (Satu baris berisi semua kolom: max_budget, allow_s3, dll)
        $rule = \App\Models\CriteriaCategoryDetail::where('scheme_type_id', $schemeId)->first();
        if (!$rule) return;

        // 2. Ambil Profil Dosen (Lokal)
        $profile = \App\Models\UserProfile::where('user_id', $this->localUser->id)->first();

        // --- CEK ANGGARAN (Kolom max_budget) ---
        $cleanBudget = (float) str_replace('.', '', $this->total_budget ?: 0);
        if ($cleanBudget > (float)$rule->max_budget) {
            $this->isEligible = false;
            $this->eligibilityMessages[] = "⚠️ Dana melebihi batas maksimal Rp " . number_format($rule->max_budget, 0, ',', '.');
        }

        // --- CEK PENDIDIKAN (Kolom allow_s2 / allow_s3) ---
        $userEdu = str_contains(strtoupper($this->localUser->name), 'DR.') ? 'S3' : 'S2';
        if ($userEdu == 'S3' && !$rule->allow_s3) {
            $this->isEligible = false;
            $this->eligibilityMessages[] = "⚠️ Skema ini tidak diperuntukkan bagi lulusan S3.";
        }
        if ($userEdu == 'S2' && !$rule->allow_s2) {
            $this->isEligible = false;
            $this->eligibilityMessages[] = "⚠️ Skema ini mewajibkan pendidikan minimal S3.";
        }
    }

    public function viewDetail($id)
    {
        // Arahkan ke form yang sama tapi dengan mode view
        return redirect()->route('dosen.usulan.form', [
            'categoryId' => $this->categoryId,
            'id_usulan' => $id,
            'mode' => 'view'
        ]);
    }
}



