<?php

namespace App\Livewire\Ketua;

use Livewire\Component;
use App\Models\{Proposal, ProposalStatus};
use App\Helper\WithComponentNameHelper;
use App\Services\NotificationService;

class FinalApprovalLivewire extends Component
{
    use WithComponentNameHelper;

    public $selectedProposal;
    public $decision_note;
    public $isReadOnly = false;
    public $activeTab  = 'pending';

    public function selectProposal($id)
    {
        $this->selectedProposal = Proposal::with([
            'leader',
            'schemeType',
            'reviewers.reviewer',
        ])->findOrFail($id);

        $this->decision_note = $this->selectedProposal->decision_note;

        $statusName      = strtolower($this->selectedProposal->status?->name ?? '');
        $this->isReadOnly = !str_contains($statusName, 'review');

        $this->dispatch('showModal', id: '#modal_putusan_ketua');
    }

    public function confirmDecision($statusFinalId)
    {
        $this->validate([
            'decision_note' => 'required|min:10',
        ], [
            'decision_note.required' => 'Wajib memberikan alasan atau catatan keputusan.',
        ]);

        // ✅ Langsung pakai ProposalStatus
        $statusFinal = ProposalStatus::find($statusFinalId);

        if (!$statusFinal) {
            $this->dispatch('showErrorAlert', 'Status tidak ditemukan.');
            return;
        }

        $sName = strtolower($statusFinal->name);

        // Tentukan status proposal baru berdasarkan nama status yang dipilih
        $targetStatusName = match (true) {
            str_contains($sName, 'approved')  => 'APPROVED',
            str_contains($sName, 'revision')  => 'REVISION',
            str_contains($sName, 'rejected')  => 'REJECTED',
            str_contains($sName, 'terima') && !str_contains($sName, 'revisi') => 'APPROVED',
            str_contains($sName, 'revisi')    => 'REVISION',
            str_contains($sName, 'tolak')     => 'REJECTED',
            default                           => 'APPROVED',
        };

        // Cari status proposal
        $proposalStatus = ProposalStatus::whereRaw('UPPER(name) = ?', [$targetStatusName])->first()
            ?? $statusFinal;

        $proposal = Proposal::findOrFail($this->selectedProposal->id);

        $proposal->update([
            'status_final_id' => $statusFinal->id,
            'status_id'       => $proposalStatus->id,
            'decision_note'   => $this->decision_note,
        ]);

        // Jika revisi, reset reviewer
        if ($targetStatusName === 'REVISION') {
            \App\Models\ProposalReviewer::where('proposal_id', $proposal->id)
                ->update([
                    'completed_at'       => null,
                    'notes'              => null,
                    'reviewer_status_id' => null,
                ]);
        }

        // Notifikasi ke pengusul
        if ($proposal->leader) {
            app(NotificationService::class)->send(
                userId: $proposal->leader->id,
                title: 'Status Final Usulan: ' . $statusFinal->name,
                message: "Usulan '{$proposal->title}' mendapat status final: {$statusFinal->name}. Catatan: {$this->decision_note}",
                options: [
                    'url'         => route('dosen.usulan.form', [
                        'categoryId' => $proposal->category_id,
                        'id_usulan'  => $proposal->id,
                    ]),
                    'icon'        => 'ph-seal-check',
                    'proposal_id' => $proposal->id,
                    'event_type'  => 'final_decision',
                    'roles'       => ['dosen'],
                ]
            );
        }

        $this->dispatch('showSuccessAlert', 'Keputusan berhasil disimpan: ' . $statusFinal->name);
        $this->dispatch('hideModal', id: '#modal_putusan_ketua');
        $this->reset(['selectedProposal', 'decision_note']);
    }

    public function render()
    {
        $pending = Proposal::with(['leader', 'status', 'schemeType'])
            ->whereHas('status', fn($q) => $q->whereIn('name', ['Review', 'Submitted']))
            ->whereNull('status_final_id') // belum diputuskan ketua
            ->orderBy('updated_at', 'desc')
            ->get();

        $history = Proposal::with(['leader', 'status', 'schemeType', 'statusFinal'])
            ->whereNotNull('status_final_id') // sudah diputuskan ketua
            ->orderBy('updated_at', 'desc')
            ->get();

        // ✅ Pakai ProposalStatus langsung, filter hanya yang relevan
        $statusFinalOptions = ProposalStatus::where(function ($q) {
            $q->whereLike('name', '%approved%')
                ->orWhereLike('name', '%rejected%')
                ->orWhereLike('name', '%revision%')
                ->orWhereLike('name', '%terima%')
                ->orWhereLike('name', '%tolak%')
                ->orWhereLike('name', '%revisi%');
        })->orderBy('id')->get();

        return view('features.ketua.approval-livewire', [
            'proposals'          => $pending,
            'history'            => $history,
            'statusFinalOptions' => $statusFinalOptions,
        ]);
    }
}
