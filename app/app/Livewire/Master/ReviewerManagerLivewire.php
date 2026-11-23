<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Http\Api\UserApi;
use App\Helper\ToolsHelper;
use App\Helper\WithComponentNameHelper;

class ReviewerManagerLivewire extends Component
{
    use WithComponentNameHelper;

    public $search = '';
    public $searchResults = [];
    public $currentUser;

    public function mount()
    {
        $this->currentUser = request()->auth;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 3) {
            $this->searchResults = [];
            return;
        }

        $token = ToolsHelper::getAuthToken();

        $response = UserApi::getUsers($token, $this->search, 5, 'Dosen');

        $this->searchResults = isset($response->data->users)
            ? collect($response->data->users)->toArray()
            : [];
    }

    public function assignAsReviewer($userData)
    {
        $user = (array) $userData;

        // ambil / buat role reviewer
        $role = Role::firstOrCreate(
            ['name' => 'reviewer'],
            ['is_active' => true]
        );

        User::updateOrCreate(
            ['email' => $user['email']],
            [
                'name' => $user['name'],
                'cis_id' => $user['id'],
                'role_id' => $role->id,
                'is_active' => true
            ]
        );

        $this->reset(['search', 'searchResults']);

        $this->dispatch('showSuccessAlert', $user['name'] . ' berhasil dijadikan Reviewer.');
    }

    public function removeReviewer($id)
    {
        $user = User::findOrFail($id);

        // 🔥 ambil role dosen secara dinamis (tidak hardcode)
        $dosenRole = Role::where('name', 'dosen')->first();

        $user->update([
            'role_id' => $dosenRole->id ?? null
        ]);

        $this->dispatch('showSuccessAlert', 'Status Reviewer dicabut.');
    }

    public function render()
    {
        $roleReviewer = Role::where('name', 'reviewer')->first();

        $currentReviewers = $roleReviewer
            ? User::where('role_id', $roleReviewer->id)->get()
            : collect();

        return view('features.master-data.reviewer.reviewer-manager-livewire', [
            'reviewers' => $currentReviewers
        ]);
    }
}



