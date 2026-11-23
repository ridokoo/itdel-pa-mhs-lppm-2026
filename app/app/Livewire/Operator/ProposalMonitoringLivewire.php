<?php

namespace App\Livewire\Operator;

use Livewire\Component;
use App\Models\Proposal;
use App\Helper\WithComponentNameHelper;
use Livewire\WithPagination;

class ProposalMonitoringLivewire extends Component
{
    use WithComponentNameHelper, WithPagination;

    public $search = '';
    public $selectedProposal = null; // Untuk menampung data detail

    // Fungsi untuk melihat detail usulan
    public function showDetail($id)
    {
        $this->selectedProposal = Proposal::with([
            'category',
            'schemeGroup',
            'schemeType',
            'scope',
            'period',
            'leader',
            'status'
        ])->findOrFail($id);

        $this->dispatch('showModal', id: '#modal_detail_usulan');
    }

    public function render()
    {
        $proposals = Proposal::with(['leader', 'status', 'schemeType'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orWhereHas('leader', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('features.operator.proposals.monitoring-livewire', [
            'proposals' => $proposals
        ]);
    }
}



