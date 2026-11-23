<?php

// ============================================================
// FILE 3: app/Livewire/Reviewer/ReviewerDashboardLivewire.php
// ============================================================

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Models\{ProposalReviewer, Proposal, ReviewerStatus, ProposalStatus, User};
use App\Helper\WithComponentNameHelper;
use App\Services\NotificationService; // ← HANYA ini, hapus SystemNotification
use Illuminate\Support\Facades\DB;

class ReviewerDashboardLivewire extends Component
{
    use WithComponentNameHelper;

    public $notes, $rev_status_id;
    public $isEdit = false;
    public $isReadOnly = false;
    public $selectedProposal;
    public $selected_id;

    public function evaluate($id)
    {
        $this->selected_id = $id;
        $assignment = ProposalReviewer::with('reviewerStatus')->findOrFail($id);
        $this->isReadOnly = !is_null($assignment->completed_at);

        $this->selectedProposal = Proposal::with([
            'leader',
            'schemeType',
            'scope',
            'period',
            'proposalMembers.user',
            'budgetItems.expenseCategory',
            'outputTargets.category',
            'partners',
        ])->findOrFail($assignment->proposal_id);

        $this->notes = $assignment->notes;
        $this->rev_status_id = $assignment->status;


        $this->dispatch('showModal', id: '#modal_evaluasi');
    }

    public function submitReview()
    {
        $this->validate([
            'rev_status_id' => 'required',
            'notes' => 'required|min:10',
        ]);

        DB::beginTransaction();
        try {
            $assignment = ProposalReviewer::findOrFail($this->selected_id);
            $proposal = Proposal::findOrFail($assignment->proposal_id);

            $assignment->update([
                'notes' => $this->notes,
                'status' => $this->rev_status_id,
                'completed_at' => now(),
            ]);

            $proposal->update(['reviewer_note' => $this->notes]);

            // Notifikasi ke dosen bahwa review sudah ada
            if ($proposal->leader) {
                app(NotificationService::class)->send(
                    userId: $proposal->leader->id,
                    title: 'Hasil Review Tersedia',
                    message: "Reviewer telah memberikan masukan untuk usulan '{$proposal->title}'.",
                    options: [
                        'url' => route('dosen.usulan.form', [
                            'categoryId' => $proposal->category_id,
                            'id_usulan' => $proposal->id,
                            'currentStep' => 7,
                        ]),
                        'icon' => 'ph-file-text',
                        'proposal_id' => $proposal->id,
                        'event_type' => 'review_result',
                        'roles' => ['dosen'],
                    ]
                );
            }

            // Cek apakah semua reviewer sudah selesai
            $isAllDone = !ProposalReviewer::where('proposal_id', $proposal->id)
                ->whereNull('completed_at')
                ->exists();

            if ($isAllDone) {
                $statusReviewing = ProposalStatus::where('name', 'Reviewing')->first();
                if ($statusReviewing) {
                    $proposal->update(['status_id' => $statusReviewing->id]);
                }

                // Notifikasi ke semua Ketua LPPM
                $ketuaUsers = User::whereHas(
                    'roles',
                    fn($q) =>
                    $q->whereLike('name', '%ketua%')
                )->get();

                foreach ($ketuaUsers as $ketua) {
                    app(NotificationService::class)->send(
                        userId: $ketua->id,
                        title: 'Usulan Siap Diputuskan',
                        message: "Usulan '{$proposal->title}' telah selesai direview dan menunggu keputusan Anda.",
                        options: [
                            'url' => route('ketua.dashboard'),
                            'icon' => 'ph-gavel',
                            'proposal_id' => $proposal->id,
                            'event_type' => 'review_complete',
                            'roles' => ['ketua'],
                        ]
                    );
                }
            }

            DB::commit();
            $this->dispatch('showSuccessAlert', 'Keputusan Review Berhasil Disimpan!');
            $this->dispatch('hideModal', id: '#modal_evaluasi');
            $this->reset(['notes', 'rev_status_id', 'selected_id', 'selectedProposal']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showErrorAlert', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('features.operator.reviewer.dashboard-livewire', [
            'myTasks' => ProposalReviewer::with(['proposal.leader', 'proposal.schemeType'])
                ->where('reviewer_id', auth()->id())
                ->orderBy('reviewer_status_id', 'asc')
                ->get(),
        ]);
    }
}



