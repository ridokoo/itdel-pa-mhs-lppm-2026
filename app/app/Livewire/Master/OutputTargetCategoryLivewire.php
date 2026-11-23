<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\{
    User,
    Role,
    OutputTargetType,
    OutputTargetCategory
};
use App\Helper\WithComponentNameHelper;

class OutputTargetCategoryLivewire extends Component
{
    use WithComponentNameHelper;

    public $output_target_type_id, $name;
    public $currentUser, $selected_id;

    public $isEdit = false;
    public $showForm = false;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
    }

    // ================= RESET =================
    public function resetFields()
    {
        $this->reset([
            'output_target_type_id',
            'name',
            'selected_id',
            'isEdit',
            'showForm'
        ]);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->resetFields();
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
            'output_target_type_id'
                => 'required|exists:output_target_types,id',

            'name'
                => 'required|max:255'
        ]);

        if (!$this->currentUser) {

            $this->dispatch(
                'showErrorAlert',
                message: 'User tidak ditemukan.'
            );

            return;
        }

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

        OutputTargetCategory::create([
            'output_target_type_id'
                => $this->output_target_type_id,

            'name'
                => $this->name,

            'created_by'
                => $localUser->id,
        ]);

        $this->dispatch(
            'showSuccessAlert',
            message: 'Kategori target berhasil ditambahkan!'
        );

        $this->resetFields();
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $this->resetFields();

        $this->isEdit = true;
        $this->showForm = true;
        $this->selected_id = $id;

        $data = OutputTargetCategory::findOrFail($id);

        $this->output_target_type_id
            = $data->output_target_type_id;

        $this->name
            = $data->name;
    }

    public function update()
    {
        $this->validate([
            'output_target_type_id'
                => 'required|exists:output_target_types,id',

            'name'
                => 'required|max:255'
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        OutputTargetCategory::findOrFail(
            $this->selected_id
        )->update([

            'output_target_type_id'
                => $this->output_target_type_id,

            'name'
                => $this->name,

            'updated_by'
                => $localUser->id ?? null,
        ]);

        $this->dispatch(
            'showSuccessAlert',
            message: 'Kategori target berhasil diperbarui!'
        );

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

            OutputTargetCategory::findOrFail(
                $this->selected_id
            )->delete();

            $this->dispatch(
                'showSuccessAlert',
                message: 'Kategori target berhasil dihapus!'
            );

        } catch (\Exception $e) {

            $this->dispatch(
                'showErrorAlert',
                message: 'Gagal hapus! Data masih digunakan.'
            );
        }

        $this->resetFields();
    }

    // ================= RENDER =================
    public function render()
    {
        return view(
            'features.master-data.output-target-category.category-livewire',
            [

                'categories' => OutputTargetCategory::with([
                        'type',
                        'creator'
                    ])
                    ->latest()
                    ->get(),

                'targetTypes' => OutputTargetType::where(
                        'is_active',
                        true
                    )->get(),
            ]
        );
    }
}



