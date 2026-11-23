<?php

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Helper\WithComponentNameHelper;
use App\Models\{
    User,
    FinalReport,
    FinalReportReview,
    ProposalStatus,
    Proposal
};
use App\Services\NotificationService;

class LaporanAkhirReviewFormLivewire extends Component
{
    use WithComponentNameHelper;

    public $id_laporan;
    public $laporan;
    public $localUser;

    public $review_notes  = '';

    public function mount($id_laporan)
    {
        $this->id_laporan  = $id_laporan;
        $this->localUser   = User::where('email', request()->auth->email)->first();

        $this->laporan = FinalReport::with([
            'proposal.budgetItems.expenseCategory',
            'proposal.outputTargets.outputTargetType',
            'proposal.leader',
            'proposal.status',
            'budgets.budgetItem.expenseCategory',
            'outputs.outputTarget.outputTargetType',
            'outputs.outputTargetStatus',
            'reviews.reviewer',
            'status',
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

        $statusName = match ($decision) {
            'approved'  => '%approved%',
            'revision'  => '%revision%',
            'selesai'   => '%selesai%',
            default     => '%submitted%',
        };

        $newStatus = ProposalStatus::where('name', 'like', $statusName)->first();
        if (!$newStatus) {
            $this->dispatch('showErrorAlert', 'Status tidak ditemukan: ' . $decision);
            return;
        }

        // Simpan review
        FinalReportReview::create([
            'final_report_id' => $this->id_laporan,
            'reviewer_id'     => $this->localUser->id,
            'status_id'       => $newStatus->id,
            'review_notes'    => $this->review_notes,
            'created_by'      => $this->localUser->id,
            'updated_by'      => $this->localUser->id,
        ]);

        // Update status laporan akhir
        $this->laporan->update(['status_id' => $newStatus->id]);

        // Jika disetujui → tandai proposal sebagai Selesai
        if ($decision === 'approved') {
            $statusSelesai = ProposalStatus::where('name', 'like', '%selesai%')->first();
            if ($statusSelesai) {
                $this->laporan->proposal->update(['status_id' => $statusSelesai->id]);
            }
        }

        // Notifikasi ke dosen
        $leader = $this->laporan->proposal->leader;
        if ($leader) {
            $messages = [
                'approved' => "Laporan akhir '{$this->laporan->proposal->title}' telah disetujui. Proposal dinyatakan SELESAI.",
                'revision' => "Laporan akhir '{$this->laporan->proposal->title}' perlu direvisi. Catatan: {$this->review_notes}",
            ];

            app(NotificationService::class)->send(
                userId: $leader->id,
                title: $decision === 'approved' ? '🎉 Laporan Akhir Disetujui' : 'Laporan Akhir Perlu Revisi',
                message: $messages[$decision] ?? '',
                options: [
                    'url'        => route('dosen.laporan-akhir.index'),
                    'icon'       => $decision === 'approved' ? 'ph-check-circle' : 'ph-warning',
                    'event_type' => 'final_report_reviewed',
                    'roles'      => ['dosen'],
                ]
            );
        }

        return redirect()->route('reviewer.laporan-akhir.index');
    }

    public function render()
    {
        return view('features.reviewer.laporan-akhir.form', [
            'laporan' => $this->laporan,
        ]);
    }
}



