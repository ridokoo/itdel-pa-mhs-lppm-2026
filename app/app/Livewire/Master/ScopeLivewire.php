<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Scope;
use App\Helper\WithComponentNameHelper;

class ScopeLivewire extends Component
{
    use WithComponentNameHelper;

    public $name;
    public $currentUser, $selected_id;

    public $isEdit = false;
    public $showForm = false;
    public $showDeleteConfirm = false;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
    }

    // ================= RESET =================
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

    // ================= CREATE =================
    public function create()
    {
        $this->resetFields();

        $this->showForm = true;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255|unique:scopes,name'
        ]);

        $role = Role::where('name', 'operator')->first();

        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name'      => $this->currentUser->name,
                'cis_id'    => $this->currentUser->id,
                'role_id'   => $role->id ?? null,
                'is_active' => true
            ]
        );

        Scope::create([
            'name'       => $this->name,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Ruang lingkup berhasil ditambahkan!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $this->resetFields();

        $this->isEdit = true;
        $this->showForm = true;
        $this->selected_id = $id;

        $data = Scope::findOrFail($id);

        $this->name = $data->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|unique:scopes,name,' . $this->selected_id
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        Scope::findOrFail($this->selected_id)->update([
            'name'       => $this->name,
            'updated_by' => $localUser->id ?? null,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Ruang lingkup berhasil diperbarui!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= DELETE =================
    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
    }

    public function destroy()
    {
        try {

            Scope::findOrFail($this->selected_id)->delete();

            $this->dispatch('showSuccessAlert', [
                'title' => 'Berhasil!',
                'text'  => 'Ruang lingkup berhasil dihapus!',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('showErrorAlert', [
                'title' => 'Gagal!',
                'text'  => 'Data masih digunakan.',
                'icon'  => 'error',
            ]);
        }

        $this->resetFields();
    }

    // ================= RENDER =================
    public function render()
    {
        return view('features.master-data.scopes.scope-livewire', [
            'scopes' => Scope::with('creator')
                ->orderBy('name', 'asc')
                ->get()
        ]);
    }
}



