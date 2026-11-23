<?php

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Helper\WithComponentNameHelper;
use App\Models\{ProgressReport, ProposalStatus, User};

class LaporanReviewIndexLivewire extends Component
{
    use WithComponentNameHelper;

    public $filterStatus = 'all'; // Ubah default ke 'all' agar riwayat langsung terlihat

    public function getLaporanProperty()
    {
        $user = User::where('email', request()->auth->email)->first();

        return ProgressReport::with([
            'proposal.schemeType',
            'proposal.category',
            'proposal.leader',
            'status',
            'reviews' => function($q) use ($user) {
                $q->where('reviewer_id', $user->id);
            }
        ])
        ->where(function($query) use ($user) {
            // Kondisi 1: Tampilkan yang statusnya masih 'submitted' (Menunggu Review)
            $query->whereHas('status', function($s) {
                $s->whereLike('name', '%submitted%');
            })
            // Kondisi 2: ATAU tampilkan yang SUDAH pernah direview oleh saya (Meskipun status sudah berubah)
            ->orWhereHas('reviews', function($r) use ($user) {
                $r->where('reviewer_id', $user->id);
            });
        })
        ->when($this->filterStatus !== 'all', function($q) {
            // Filter tambahan jika user memilih status spesifik di dropdown
            $q->whereHas('status', fn($s) => $s->where('name', 'like', "%{$this->filterStatus}%"));
        })
        ->orderByDesc('submitted_at')
        ->get();
    }

    public function render()
    {
        return view('features.reviewer.laporan.index', [
            'laporanList' => $this->laporan,
            'statuses'    => ProposalStatus::orderBy('name')->get(),
        ]);
    }
}



