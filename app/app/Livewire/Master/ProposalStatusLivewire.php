<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\ProposalStatus;
use App\Helper\WithComponentNameHelper;

class ProposalStatusLivewire extends Component
{
    use WithComponentNameHelper;

    public $name;
    public $currentUser;
    public $selected_id;

    public $isEdit = false;
    public $showForm = false;
    public $showDeleteConfirm = false;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
    }

    public function resetFields()
    {
        $this->reset([
            'name',
            'isEdit',
            'selected_id',
            'showForm',
            'showDeleteConfirm'
        ]);

        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetFields();
    }

    public function create()
    {
        $this->resetFields();
        $this->showForm = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255|unique:proposal_status,name'
        ]);

        $role = Role::firstOrCreate(
            ['name' => 'operator'],
            ['is_active' => true]
        );

        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name'      => $this->currentUser->name ?? 'User',
                'cis_id'    => $this->currentUser->id ?? null,
                'role_id'   => $role->id ?? null,
                'is_active' => true
            ]
        );

        ProposalStatus::create([
            'name'       => $this->name,
            'created_by' => $localUser->id,
        ]);

        session()->flash(
            'success',
            'Status proposal berhasil ditambahkan!'
        );

        $this->resetFields();
    }

    public function edit($id)
    {
        $this->resetErrorBag();

        $this->isEdit = true;
        $this->selected_id = $id;
        $this->showForm = true;
        $this->showDeleteConfirm = false;

        $data = ProposalStatus::findOrFail($id);

        $this->name = $data->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|unique:proposal_status,name,' . $this->selected_id
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        ProposalStatus::findOrFail($this->selected_id)->update([
            'name'       => $this->name,
            'updated_by' => $localUser->id ?? null,
        ]);

        session()->flash(
            'success',
            'Status proposal berhasil diperbarui!'
        );

        $this->resetFields();
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->showDeleteConfirm = true;
        $this->showForm = false;
    }

    public function destroy()
    {
        $status = ProposalStatus::findOrFail($this->selected_id);

        if ($status->proposals()->exists()) {
            $this->dispatch('showErrorAlert', 'Gagal hapus! Status ini masih digunakan oleh proposal aktif. Ubah status proposal terkait terlebih dahulu.');
            return;
        }

        try {
            $status->delete();

            ProposalStatus::findOrFail($this->selected_id)->delete();

            session()->flash(
                'success',
                'Status proposal berhasil dihapus!'
            );
        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Gagal hapus! Status ini sudah digunakan dalam data Proposal atau History.'
            );
        }

        $this->resetFields();
    }

    public function render()
    {
        return view(
            'features.master-data.proposal-status.proposal-status-livewire',
            [
                'statuses' => ProposalStatus::with('creator')
                    ->orderBy('id', 'asc')
                    ->get()
            ]
        );
    }
}



