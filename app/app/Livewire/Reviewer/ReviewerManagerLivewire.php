<?php

namespace App\Livewire\Reviewer;

use Livewire\Component;
use App\Models\{User, Role, ProposalPeriod}; // Pastikan import ProposalPeriod
use App\Http\Api\UserApi;
use App\Helper\{ToolsHelper, WithComponentNameHelper};
use Illuminate\Support\Facades\Hash;

class ReviewerManagerLivewire extends Component
{
    use WithComponentNameHelper;

    public $search = '';
    public $searchResults = [];
    public $isPeriodOpen = false; // Status periode pendaftaran

    // Properti Navigasi
    public $viewMode = 'list_periods'; // 'list_periods' atau 'manage_reviewers'
    public $selectedPeriod;

    public function mount()
    {
        // CEK APAKAH ADA PERIODE YANG SEDANG AKTIF
        $now = now()->format('Y-m-d');
        $this->isPeriodOpen = ProposalPeriod::where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->exists();
    }

    public function selectPeriod($periodId)
    {
        $period = ProposalPeriod::findOrFail($periodId);
        $now = now()->format('Y-m-d');

        // Logika pengecekan: is_active harus true DAN tanggal sekarang harus di dalam range
        $isDateValid = ($now >= $period->start_date && $now <= $period->end_date);

        if (!$period->is_active || !$isDateValid) {
            $this->dispatch('showErrorAlert', 'Maaf, periode ini sudah tidak aktif atau telah berakhir.');
            return;
        }

        $this->selectedPeriod = $period;
        $this->viewMode = 'manage_reviewers';
    }

    public function backToPeriods()
    {
        $this->viewMode = 'list_periods';
        $this->selectedPeriod = null;
        $this->reset(['search', 'searchResults']);
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 3) {
            $this->searchResults = [];
            return;
        }

        $token = ToolsHelper::getAuthToken();
        $response = UserApi::getUsers($token, $this->search, 5, 'Dosen');

        if (isset($response->data->users)) {
            $this->searchResults = collect($response->data->users)->toArray();
        }
    }

    public function assignAsReviewer($userData)
    {
        $user = (array) $userData;
        $roleReviewer = Role::where('name', 'reviewer')->first();

        $localUser = User::updateOrCreate(
            ['email' => $user['email']],
            [
                'name'     => $user['name'],
                'cis_id'   => $user['id'],
                'username' => $user['username'] ?? explode('@', $user['email'])[0],
                'alias'    => $user['alias'] ?? 'Dosen',
                'password' => Hash::make('dummy-password'),
                'is_active' => true
            ]
        );

        $localUser->roles()->syncWithoutDetaching([$roleReviewer->id]);

        $this->reset(['search', 'searchResults']);
        $this->dispatch('showSuccessAlert', 'Dosen berhasil ditambahkan ke daftar Reviewer.');
    }

    public function removeReviewer($id)
    {
        $user = User::findOrFail($id);
        $roleReviewer = Role::where('name', 'reviewer')->first();

        if ($roleReviewer) {
            // PERBAIKAN: Gunakan detach untuk tabel pivot user_roles
            $user->roles()->detach($roleReviewer->id);
        }

        $this->dispatch('showSuccessAlert', 'Akses Reviewer telah dicabut.');
    }

    public function render()
    {
        $roleReviewer = Role::where('name', 'reviewer')->first();

        return view('features.master-data.reviewer.reviewer-manager-livewire', [
            'periods' => ProposalPeriod::orderBy('year', 'desc')->get(),
            'reviewers' => $roleReviewer ? User::whereHas('roles', fn($q) => $q->where('roles.id', $roleReviewer->id))->get() : collect()
        ]);
    }
}



