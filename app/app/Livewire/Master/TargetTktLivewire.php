<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\TargetTkt;
use App\Helper\WithComponentNameHelper;

class TargetTktLivewire extends Component
{
    use WithComponentNameHelper;

    public $level, $name, $description;
    public $currentUser, $selected_id;

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
            'level',
            'name',
            'description',
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
            'level'       => 'required|integer|min:1|max:9|unique:target_tkts,level',
            'name'        => 'required|max:255',
            'description' => 'nullable'
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

        TargetTkt::create([
            'level'       => $this->level,
            'name'        => $this->name,
            'description' => $this->description,
            'created_by'  => $localUser->id,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Level TKT berhasil ditambahkan!',
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

        $data = TargetTkt::findOrFail($id);

        $this->level       = $data->level;
        $this->name        = $data->name;
        $this->description = $data->description;
    }

    public function update()
    {
        $this->validate([
            'level'       => 'required|integer|min:1|max:9|unique:target_tkts,level,' . $this->selected_id,
            'name'        => 'required|max:255',
            'description' => 'nullable'
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        TargetTkt::findOrFail($this->selected_id)->update([
            'level'       => $this->level,
            'name'        => $this->name,
            'description' => $this->description,
            'updated_by'  => $localUser->id ?? null,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Level TKT berhasil diperbarui!',
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

            TargetTkt::findOrFail($this->selected_id)->delete();

            $this->dispatch('showSuccessAlert', [
                'title' => 'Berhasil!',
                'text'  => 'Level TKT berhasil dihapus!',
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
        return view('features.master-data.target-tkt.tkt-livewire', [
            'tkts' => TargetTkt::with('creator')
                ->orderBy('level', 'asc')
                ->get()
        ]);
    }
}



