<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\ProposalMemberStatus;
use App\Helper\WithComponentNameHelper;

class MemberStatusLivewire extends Component
{
    use WithComponentNameHelper;

    public $name, $currentUser, $selected_id;

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
            'name' => 'required|max:255|unique:proposal_member_status,name'
        ]);

        $role = Role::where('name', 'operator')->first();

        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name'      => $this->currentUser->name ?? 'User',
                'cis_id'    => $this->currentUser->id ?? null,
                'role_id'   => $role->id ?? null,
                'is_active' => true
            ]
        );

        ProposalMemberStatus::create([
            'name'       => $this->name,
            'created_by' => $localUser->id,
        ]);

        session()->flash('success', 'Status anggota berhasil ditambahkan!');

        $this->resetFields();
    }

    public function edit($id)
    {
        $this->resetFields();

        $this->isEdit = true;
        $this->selected_id = $id;
        $this->showForm = true;

        $data = ProposalMemberStatus::findOrFail($id);

        $this->name = $data->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|unique:proposal_member_status,name,' . $this->selected_id
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        ProposalMemberStatus::findOrFail($this->selected_id)->update([
            'name'       => $this->name,
            'updated_by' => $localUser->id ?? null,
        ]);

        session()->flash('success', 'Status anggota berhasil diperbarui!');

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
        try {

            ProposalMemberStatus::findOrFail(
                $this->selected_id
            )->delete();

            session()->flash(
                'success',
                'Status anggota berhasil dihapus!'
            );

        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Gagal hapus! Status ini sudah digunakan pada data Anggota Proposal.'
            );
        }

        $this->resetFields();
    }

    public function render()
    {
        return view(
            'features.master-data.member-status.member-status-livewire',
            [
                'statuses' => ProposalMemberStatus::with('creator')
                    ->orderBy('id', 'asc')
                    ->get()
            ]
        );
    }
}



