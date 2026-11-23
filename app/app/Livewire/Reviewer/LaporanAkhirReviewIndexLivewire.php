<?php

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Helper\WithComponentNameHelper;
use App\Models\{FinalReport, ProposalStatus, User};

class LaporanAkhirReviewIndexLivewire extends Component
{
    use WithComponentNameHelper;

    public $filterStatus = 'submitted';

    public function getLaporanProperty()
    {
        // 1. Ambil data user yang sedang login (sebagai reviewer)
        $user = User::where('email', request()->auth->email)->first();

        return FinalReport::with([
            'proposal.schemeType',
            'proposal.category',
            'proposal.leader',
            'status',
            'reviews' // Eager load untuk mengecek status review di blade
        ])
            ->where(function ($query) use ($user) {
                if ($this->filterStatus === 'submitted') {
                    // Tampilkan yang statusnya masih submitted (Menunggu)
                    $query->whereHas('status', function ($s) {
                        $s->whereLike('name', '%submitted%');
                    })
                        // ATAU tampilkan yang SUDAH pernah saya review (Riwayat)
                        ->orWhereHas('reviews', function ($r) use ($user) {
                            $r->where('reviewer_id', $user->id);
                        });
                }
            })
            ->orderByDesc('submitted_at')
            ->get();
    }

    public function render()
    {
        return view('features.reviewer.laporan-akhir.index', [
            'laporanList' => $this->laporan,
        ]);
    }
}



