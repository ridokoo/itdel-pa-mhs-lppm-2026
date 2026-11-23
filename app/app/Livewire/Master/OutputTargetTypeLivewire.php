<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\OutputTargetType;
use App\Helper\WithComponentNameHelper;

class OutputTargetTypeLivewire extends Component
{
    use WithComponentNameHelper;

    public $name, $currentUser, $selected_id;

    public $isEdit = false;
    public $showForm = false;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
    }

    public function resetFields()
    {
        $this->reset([
            'name',
            'isEdit',
            'selected_id'
        ]);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    // ================= CREATE =================
    public function create()
    {
        $this->resetFields();

        $this->showForm = true;
    }

    // ================= CANCEL =================
    public function cancel()
    {
        $this->resetFields();

        $this->showForm = false;
    }

    // ================= STORE =================
    public function store()
    {
        $this->validate([
            'name' => 'required|max:100|unique:output_target_types,name'
        ]);

        if (!$this->currentUser) {

            $this->dispatch(
                'showErrorAlert',
                [
                    'title' => 'Gagal!',
                    'text'  => 'User tidak ditemukan.',
                    'icon'  => 'error'
                ]
            );

            return;
        }

        // ROLE
        $role = Role::firstOrCreate(
            ['name' => 'operator'],
            ['is_active' => true]
        );

        // USER
        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name'      => $this->currentUser->name ?? 'User',
                'cis_id'    => $this->currentUser->id ?? null,
                'role_id'   => $role->id ?? null,
                'is_active' => true
            ]
        );

        // SAVE
        OutputTargetType::create([
            'name'       => $this->name,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch(
            'showSuccessAlert',
            [
                'title' => 'Berhasil!',
                'text'  => 'Jenis luaran berhasil ditambahkan.',
                'icon'  => 'success'
            ]
        );

        $this->resetFields();

        $this->showForm = false;
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $this->resetErrorBag();

        $this->isEdit = true;
        $this->selected_id = $id;
        $this->showForm = true;

        $data = OutputTargetType::findOrFail($id);

        $this->name = $data->name;
    }

    // ================= UPDATE =================
    public function update()
    {
        $this->validate([
            'name' => 'required|max:100|unique:output_target_types,name,' . $this->selected_id
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        OutputTargetType::findOrFail($this->selected_id)->update([
            'name'       => $this->name,
            'updated_by' => $localUser->id ?? null
        ]);

        $this->dispatch(
            'showSuccessAlert',
            [
                'title' => 'Berhasil!',
                'text'  => 'Jenis luaran berhasil diperbarui.',
                'icon'  => 'success'
            ]
        );

        $this->resetFields();

        $this->showForm = false;
    }

    // ================= DELETE =================
    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
    }

    public function destroy()
    {
        try {

            OutputTargetType::findOrFail(
                $this->selected_id
            )->delete();

            $this->dispatch(
                'showSuccessAlert',
                [
                    'title' => 'Berhasil!',
                    'text'  => 'Data berhasil dihapus.',
                    'icon'  => 'success'
                ]
            );

        } catch (\Exception $e) {

            $this->dispatch(
                'showErrorAlert',
                [
                    'title' => 'Gagal!',
                    'text'  => 'Data ini masih digunakan oleh Kategori Luaran.',
                    'icon'  => 'error'
                ]
            );
        }
    }

    // ================= RENDER =================
    public function render()
    {
        return view(
            'features.master-data.output-target-type.type-livewire',
            [
                'types' => OutputTargetType::with('creator')
                    ->latest()
                    ->get()
            ]
        );
    }
}



