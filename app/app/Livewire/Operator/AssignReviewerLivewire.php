<?php

// ============================================================
// FILE 2: app/Livewire/Operator/AssignReviewerLivewire.php
// ============================================================

namespace App\Livewire\Operator;

use Livewire\Component;
use App\Models\{User, Proposal, ProposalReviewer, ProposalStatus};
use App\Helper\WithComponentNameHelper;
use App\Services\NotificationService; // ← HANYA ini, hapus SystemNotification

class AssignReviewerLivewire extends Component
{
    use WithComponentNameHelper;

    public $selectedProposalId;
    public $reviewer_id;
    public $search = '';

    public function assign($proposalId): void
    {
        $this->selectedProposalId = $proposalId;

        $this->validate([
            'selectedProposalId' => 'required|exists:proposals,id',
            'reviewer_id' => 'required|exists:users,id',
        ]);

        $proposal = Proposal::with('proposalMembers')->findOrFail($proposalId);
        $memberIds = $proposal->proposalMembers->pluck('user_id')->toArray();
        $allIds = array_unique(array_merge([$proposal->leader_id], $memberIds));

        if (in_array((int) $this->reviewer_id, $allIds)) {
            $this->dispatch('showErrorAlert', 'Reviewer tidak boleh merupakan ketua atau anggota dari usulan ini!');
            return;
        }

        $sudahAda = ProposalReviewer::where('proposal_id', $proposalId)
            ->where('reviewer_id', $this->reviewer_id)
            ->exists();

        if ($sudahAda) {
            $this->dispatch('showErrorAlert', 'Reviewer ini sudah ditugaskan pada usulan tersebut.');
            return;
        }

        ProposalReviewer::create([
    'proposal_id' => $proposalId,
    'reviewer_id' => $this->reviewer_id,
    'assigned_by' => auth()->id(),
    'status' => null,
]);

        $statusReview = ProposalStatus::where('name', 'like', '%Review%')->first();
        if ($statusReview) {
            $proposal->update(['status_id' => $statusReview->id]);
        }

        // Notifikasi ke reviewer lewat service
        $reviewer = User::find($this->reviewer_id);
        if ($reviewer) {
            app(NotificationService::class)->send(
                userId: $reviewer->id,
                title: 'Tugas Review Baru',
                message: "Anda ditugaskan menjadi reviewer untuk usulan '{$proposal->title}'.",
                options: [
                    'url' => route('reviewer.dashboard'),
                    'icon' => 'ph-clipboard-text',
                    'proposal_id' => $proposalId,
                    'event_type' => 'reviewer_assignment',
                    'roles' => ['reviewer'],
                ]
            );
        }

        $this->reviewer_id = '';
        $this->dispatch('showSuccessAlert', 'Plotting berhasil.');
    }

    public function removeAssignment($id): void
    {
        $assignment = ProposalReviewer::find($id);
        if ($assignment) {
            $assignment->delete();
            $this->dispatch('showSuccessAlert', 'Penugasan reviewer berhasil dibatalkan.');
        }
    }

    public function render()
    {
        $proposals = Proposal::with(['leader', 'proposalMembers.user', 'reviewers.user'])
            ->where('status_id', '!=', 1)
            ->where('title', 'like', '%' . $this->search . '%')
            ->get();

        $availableReviewers = User::whereHas(
            'roles',
            fn($q) =>
            $q->where('name', 'reviewer')
        )->get();

        return view('features.operator.reviewer.assign-reviewer-livewire', [
            'proposals' => $proposals,
            'availableReviewers' => $availableReviewers,
        ]);
    }
}



