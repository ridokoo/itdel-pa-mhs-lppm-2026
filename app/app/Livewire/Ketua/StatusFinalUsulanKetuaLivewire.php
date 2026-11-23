<?php

namespace App\Livewire\Ketua;

use Livewire\Component;
use App\Models\Proposal;
use App\Models\ProposalStatus;
use App\Services\NotificationService;

class StatusFinalUsulanKetuaLivewire extends Component
{
    public $activeTab        = 'pending';
    public $selectedProposal = null;
    public $selectedStatusId = null;
    public $catatan          = '';
    public $showModal        = false;

    public function selectProposal($id)
    {
        $this->selectedProposal = Proposal::with([
            'leader',
            'schemeType',
            'statusFinal',
        ])->findOrFail($id);

        $this->selectedStatusId = null;
        $this->catatan          = '';
        $this->showModal        = true;
    }

    public function closeModal()
    {
        $this->reset(['selectedProposal', 'selectedStatusId', 'catatan', 'showModal']);
        $this->resetErrorBag();
    }

    public function confirm()
    {
        $this->validate([
            'selectedStatusId' => 'required|exists:proposal_status,id',
            'catatan'          => 'nullable|string|max:500',
        ], [
            'selectedStatusId.required' => 'Wajib memilih status final.',
        ]);

        // ✅ Pakai ProposalStatus langsung
        $status   = ProposalStatus::findOrFail($this->selectedStatusId);
        $proposal = Proposal::findOrFail($this->selectedProposal->id);

        $proposal->update([
            'status_final_id' => $status->id,
            'decision_note'   => $this->catatan,
        ]);

        if ($proposal->leader) {
            app(NotificationService::class)->send(
                userId: $proposal->leader->id,
                title: 'Status Final Usulan: ' . $status->name,
                message: "Usulan '{$proposal->title}' telah mendapat status final: {$status->name}." .
                    ($this->catatan ? " Catatan: {$this->catatan}" : ''),
                options: [
                    'url'         => route('dosen.usulan.form', [
                        'categoryId' => $proposal->category_id,
                        'id_usulan'  => $proposal->id,
                    ]),
                    'icon'        => 'ph-seal-check',
                    'proposal_id' => $proposal->id,
                    'event_type'  => 'status_final',
                    'roles'       => ['dosen'],
                ]
            );
        }

        $this->dispatch('showSuccessAlert', 'Status final berhasil disimpan: ' . $status->name);
        $this->closeModal();
    }

    public function render()
    {
        $pending = Proposal::with(['leader', 'schemeType', 'statusFinal'])
            ->whereNull('status_final_id')
            ->whereHas('status', fn($q) => $q->whereIn('name', ['APPROVED', 'REVISION', 'REJECTED']))
            ->orderBy('updated_at', 'desc')
            ->get();

        // ✅ Ganti statusFinalResult → statusFinal
        $history = Proposal::with(['leader', 'schemeType', 'statusFinal'])
            ->whereNotNull('status_final_id')
            ->orderBy('updated_at', 'desc')
            ->get();

        $statusOptions = ProposalStatus::where(function ($q) {
            $q->whereLike('name', '%approved%')
                ->orWhereLike('name', '%rejected%')
                ->orWhereLike('name', '%revision%');
        })->get();

        return view('features.ketua.status-final-usulan-livewire', [
            'proposals'     => $pending,
            'history'       => $history,
            'statusOptions' => $statusOptions,
        ]);
    }
}



