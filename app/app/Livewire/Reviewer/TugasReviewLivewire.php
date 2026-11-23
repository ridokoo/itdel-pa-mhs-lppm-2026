<?php

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Models\ProposalReviewer;


class TugasReviewLivewire extends Component
{

    public function render()
    {
        // Sistem hanya menampilkan usulan yang ditugaskan ke Dosen C (User yang sedang login)
        $myTasks = \App\Models\ProposalReviewer::with(['proposal'])
            ->where('reviewer_id', auth()->id()) // Filter berdasarkan ID Dosen C
            ->where('status', '!=', 'completed')
            ->get();

        return view('features.reviewer.tugas-index', ['tasks' => $myTasks]);
    }
}



