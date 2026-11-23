<?php
namespace App\Livewire\Dosen;
use Livewire\Component;
use App\Models\{User, Proposal};
class LaporanKemajuanIndexLivewire extends Component
{
    public $categoryId;
    public $filterYear    = 'all';
    public $availableYears = [];
    public function mount($categoryId = null)
    {
        $this->categoryId     = $categoryId;
        $this->availableYears = $this->loadAvailableYears();
    }
    private function loadAvailableYears(): array
    {
        $user = auth()->user();
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
    $user = auth()->user();
    {
        $user = User::where('email', request()->auth->email)->first();

        return Proposal::where('leader_id', $user->id)
            ->with([
                'progressReports.status',
                'progressReports.reviews.reviewer',
                'schemeType',
                'scope',
                'status'
                ])
            // Filter hanya status yang Disetujui/Approved
            ->whereHas('status', function($q) {
                $q->whereLike('name', '%approved%')
                  ->orWhereLike('name', '%setuju%');

            })
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when(
                $this->filterYear !== 'all',
                fn($q) => $q->whereRaw('YEAR(created_at) = ?', [$this->filterYear]) // ✅ MySQL
            )
            ->get();
    }

    return Proposal::where('leader_id', $user->id)
        ->with([
            'progressReports.status',
            'progressReports.reviews.reviewer',
            'schemeType',
            'scope',
            'status'
        ])
        // Filter status Disetujui (id 5) dan APPROVED (id 9)
        ->whereIn('status_id', [5, 9])
        ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
        ->when(
            $this->filterYear !== 'all',
            fn($q) => $q->whereRaw('EXTRACT(YEAR FROM created_at)::int = ?', [$this->filterYear])
        )
        ->get();
}
    public function render()
    {
        return view('features.dosen.laporan.index', [
            'proposals'      => $this->proposals,
            'availableYears' => $this->availableYears,
            'categoryId'     => $this->categoryId,
        ]);
    }
}
