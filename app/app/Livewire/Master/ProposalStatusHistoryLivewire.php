<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\ProposalStatusHistory as HistoryModel;
use App\Models\Proposal;
use App\Models\ProposalStatus;
use Illuminate\Support\Facades\Auth;

class ProposalStatusHistoryLivewire extends Component
{
    public $proposal_id        = '';
    public $proposal_status_id = '';
    public $note               = '';

    public $histories  = [];
    public $proposals  = [];
    public $statuses   = [];

    public $editingId  = null;
    public $showForm   = false;
    public $isEdit     = false;

    protected $rules = [
        'proposal_id'        => 'required|exists:proposals,id',
        'proposal_status_id' => 'required|exists:proposal_status;;;;;;;;;;;;;;;;;,id',
        'note'               => 'nullable|string|max:1000',
    ];

    public static function getComponentName(): string
    {
        return 'master.proposal-status-history-livewire';
    }

    public function mount()
    {
        $this->proposals = Proposal::all();
        $this->statuses  = ProposalStatus::all();
        $this->loadHistories();
    }

    public function loadHistories()
    {
        $this->histories = HistoryModel::with(['proposal', 'proposalStatus', 'changedBy'])
            ->latest('created_at')
            ->get();
    }

    public function showCreate()
    {
        $this->resetFields();
        $this->showForm = true;
        $this->isEdit   = false;
    }

    public function showEdit($id)
    {
        $history                  = HistoryModel::findOrFail($id);
        $this->editingId          = $id;
        $this->proposal_id        = $history->proposal_id;
        $this->proposal_status_id = $history->proposal_status_id;
        $this->note               = $history->note;
        $this->showForm           = true;
        $this->isEdit             = true;
    }

    public function store()
    {
        $this->validate();

        HistoryModel::create([
            'proposal_id'        => $this->proposal_id,
            'proposal_status_id' => $this->proposal_status_id,
            'changed_by'         => Auth::id(),
            'note'               => $this->note,
            'created_at'         => now(),
        ]);

        $this->loadHistories();
        $this->resetFields();

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'History berhasil ditambahkan.',
            'icon'  => 'success',
        ]);
    }

    public function confirmUpdate()
    {
        $this->dispatch('triggerUpdateConfirm');
    }

    public function update()
    {
        $this->validate();

        HistoryModel::findOrFail($this->editingId)->update([
            'proposal_id'        => $this->proposal_id,
            'proposal_status_id' => $this->proposal_status_id,
            'note'               => $this->note,
        ]);

        $this->loadHistories();
        $this->resetFields();

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'History berhasil diperbarui.',
            'icon'  => 'success',
        ]);
    }

    public function delete($id)
    {
        HistoryModel::findOrFail($id)->delete();
        $this->loadHistories();

        $this->dispatch('swal:modal', [
            'title' => 'Dihapus!',
            'text'  => 'History berhasil dihapus.',
            'icon'  => 'success',
        ]);
    }

    public function resetFields()
    {
        $this->editingId          = null;
        $this->proposal_id        = '';
        $this->proposal_status_id = '';
        $this->note               = '';
        $this->showForm           = false;
        $this->isEdit             = false;
    }

    public function render()
    {
        return view('features.master-data.proposal-status-history.proposal-status-history-livewire');
    }
}



