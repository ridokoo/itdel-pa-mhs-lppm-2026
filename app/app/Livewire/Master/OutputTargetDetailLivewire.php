<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\{
    User,
    Role,
    OutputTargetCategory,
    OutputTargetDetail
};
use App\Helper\WithComponentNameHelper;

class OutputTargetDetailLivewire extends Component
{
    use WithComponentNameHelper;

    public $output_target_category_id, $name;
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
            'output_target_category_id',
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
            'output_target_category_id'
                => 'required|exists:output_target_categories,id',

            'name' => 'required|max:255'
        ]);

        if (!$this->currentUser) {

            $this->dispatch('showErrorAlert', [
                'title' => 'Gagal!',
                'text'  => 'User tidak ditemukan.',
                'icon'  => 'error',
            ]);

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

        OutputTargetDetail::create([
            'output_target_category_id'
                => $this->output_target_category_id,

            'name'       => $this->name,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Detail luaran berhasil ditambahkan!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $this->resetFields();

        $data = OutputTargetDetail::findOrFail($id);

        $this->selected_id = $data->id;

        $this->output_target_category_id
            = $data->output_target_category_id;

        $this->name = $data->name;

        $this->isEdit = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'output_target_category_id'
                => 'required|exists:output_target_categories,id',

            'name' => 'required|max:255'
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        OutputTargetDetail::findOrFail(
            $this->selected_id
        )->update([
            'output_target_category_id'
                => $this->output_target_category_id,

            'name'       => $this->name,
            'updated_by' => $localUser->id ?? null,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Detail luaran berhasil diperbarui!',
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

            OutputTargetDetail::findOrFail(
                $this->selected_id
            )->delete();

            $this->dispatch('showSuccessAlert', [
                'title' => 'Berhasil!',
                'text'  => 'Detail luaran berhasil dihapus!',
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
        return view(
            'features.master-data.output-target-detail.detail-livewire',
            [

                'details' => OutputTargetDetail::with([
                        'category.type',
                        'creator'
                    ])
                    ->latest()
                    ->get(),

                'categories' => OutputTargetCategory::with('type')
                    ->where('is_active', true)
                    ->get()
            ]
        );
    }
}



