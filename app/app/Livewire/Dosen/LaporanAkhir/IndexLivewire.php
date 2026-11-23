<?php

namespace App\Livewire\Dosen\LaporanAkhir;

use Livewire\Component;
use App\Models\{User, Proposal};

class IndexLivewire extends Component
{
    public $categoryId;
    public $filterYear     = 'all';
    public $availableYears = [];

    public function mount($categoryId = null)
    {
        $this->categoryId     = $categoryId;
        $this->availableYears = $this->loadAvailableYears();
    }

    private function loadAvailableYears(): array
    {
        $user = User::where('email', request()->auth->email)->first();

        return Proposal::where('leader_id', $user->id)
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->whereNotNull('created_at')
            ->selectRaw('YEAR(created_at) as year') // ✅ MySQL
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
    }

    public function getProposalsProperty()
    {
        $user = User::where('email', request()->auth->email)->first();

        return Proposal::where('leader_id', $user->id)
            ->with(['finalReport', 'finalReport.status', 'schemeType', 'scope', 'status'])
            ->whereHas('progressReports', function ($q) {
                $q->whereHas('status', function ($sq) {
                    $sq->where('name', 'like', '%approved%')   // ✅ ilike → like
                        ->orWhere('name', 'like', '%setuju%');
                });
            })
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when(
                $this->filterYear !== 'all',
                fn($q) => $q->whereRaw('YEAR(created_at) = ?', [$this->filterYear]) // ✅ EXTRACT → YEAR
            )
            ->get();
    }

    public function render()
    {
        // Merender UI
        return view('features.dosen.laporan-akhir.index', [
            'proposals'      => $this->proposals,
            'availableYears' => $this->availableYears,
            'categoryId'     => $this->categoryId,
        ]);
    }
}
