<?php

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Helper\WithComponentNameHelper;
use App\Models\{
    User, ProgressReport, ProgressReportReview,
    ProposalStatus
};
use App\Services\NotificationService;

class LaporanReviewFormLivewire extends Component
{
    use WithComponentNameHelper;

    public $id_laporan;
    public $laporan;
    public $localUser;

    public $review_notes = '';
    public $review_status = ''; // 'approved' atau 'revision'

    public function mount($id_laporan)
    {
        $this->id_laporan  = $id_laporan;
        $this->localUser   = User::where('email', request()->auth->email)->first();

        $this->laporan = ProgressReport::with([
            'proposal.budgetItems.expenseCategory',
            'proposal.outputTargets.outputTargetType',
            'proposal.leader',
            'budgets.budgetItem.expenseCategory',
            'outputs.proposalOutputTarget.outputTargetType',
            'outputs.outputTargetStatus',
            'status',
             'reviews.reviewer',
        ])->findOrFail($this->id_laporan);
    }

    public function submitReview(string $decision)
    {
        $this->validate([
            'review_notes' => $decision === 'revision' ? 'required|min:10' : 'nullable',
        ], [
            'review_notes.required' => 'Catatan wajib diisi jika meminta revisi.',
            'review_notes.min'      => 'Catatan minimal 10 karakter.',
        ]);

        // Tentukan status baru
        $statusName = $decision === 'approved' ? '%approved%' : '%revision%';
        $newStatus  = ProposalStatus::where('name', 'like', $statusName)->first();

        if (!$newStatus) {
            $this->dispatch('showErrorAlert', 'Status tidak ditemukan di database.');
            return;
        }

        // Simpan review
        ProgressReportReview::create([
            'progress_report_id' => $this->id_laporan,
            'reviewer_id'        => $this->localUser->id,
            'status_id'          => $newStatus->id,
            'review_notes'       => $this->review_notes,
            'created_by'         => $this->localUser->id,
            'updated_by'         => $this->localUser->id,
        ]);

        // Update status laporan
        $this->laporan->update(['status_id' => $newStatus->id]);

        // Notifikasi ke dosen ketua
        $leader = $this->laporan->proposal->leader;
        if ($leader) {
            $msg = $decision === 'approved'
                ? "Laporan kemajuan '{$this->laporan->proposal->title}' telah disetujui oleh reviewer."
                : "Laporan kemajuan '{$this->laporan->proposal->title}' perlu direvisi. Catatan: {$this->review_notes}";

            app(NotificationService::class)->send(
                userId: $leader->id,
                title: $decision === 'approved' ? 'Laporan Disetujui' : 'Laporan Perlu Revisi',
                message: $msg,
                options: [
                    'url'        => route('dosen.laporan-kemajuan.index', ['categoryId' => $this->laporan->proposal->category_id]),
                    'icon'       => $decision === 'approved' ? 'ph-check-circle' : 'ph-warning',
                    'event_type' => 'progress_report_reviewed',
                    'roles'      => ['dosen'],
                ]
            );
        }

        return redirect()->route('reviewer.laporan.index');
    }

    public function render()
    {
        return view('features.reviewer.laporan.form', [
            'laporan' => $this->laporan,
        ]);
    }
}



