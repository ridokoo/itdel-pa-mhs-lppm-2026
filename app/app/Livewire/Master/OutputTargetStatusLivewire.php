<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{
    User,
    Role,
    OutputTargetStatus
};
use App\Helper\WithComponentNameHelper;

class OutputTargetStatusLivewire extends Component
{
    use WithComponentNameHelper;

    public $name;
    public $currentUser;
    public $selected_id;

    public $isEdit = false;
    public $showForm = false;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
    }

    /**
     * Reset Form
     */
    public function resetFields()
    {
        $this->reset([
            'name',
            'selected_id',
            'isEdit',
            'showForm',
        ]);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Cancel Form
     */
    public function cancel()
    {
        $this->resetFields();
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        $this->resetFields();

        $this->showForm = true;
    }

    /**
     * Store Data
     */
    public function store()
    {
        $this->validate([
            'name' => 'required|max:255|unique:output_target_statuses,name'
        ]);

        $role = Role::where('name', 'operator')->first();

        $localUser = User::firstOrCreate(
            [
                'email' => $this->currentUser->email
            ],
            [
                'name'      => $this->currentUser->name,
                'cis_id'    => $this->currentUser->id,
                'role_id'   => $role->id ?? null,
                'is_active' => true
            ]
        );

        OutputTargetStatus::create([
            'name'       => $this->name,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Status capaian berhasil ditambahkan!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    /**
     * Edit Form
     */
    public function edit($id)
    {
        $this->resetFields();

        $this->isEdit = true;
        $this->showForm = true;
        $this->selected_id = $id;

        $data = OutputTargetStatus::findOrFail($id);

        $this->name = $data->name;
    }

    /**
     * Update Data
     */
    public function update()
    {
        $this->validate([
            'name' =>
                'required|max:255|unique:output_target_statuses,name,' .
                $this->selected_id
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        OutputTargetStatus::findOrFail(
            $this->selected_id
        )->update([
            'name'       => $this->name,
            'updated_by' => $localUser->id ?? null,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Status capaian berhasil diperbarui!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    /**
     * Delete Confirm
     */
    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
    }

    /**
     * Destroy Data
     */
    #[On('deleteConfirmed')]
    public function destroy($id = null)
    {
        try {

            $deleteId = $id ?? $this->selected_id;

            OutputTargetStatus::findOrFail($deleteId)->delete();

            $this->dispatch('showSuccessAlert', [
                'title' => 'Berhasil!',
                'text'  => 'Data berhasil dihapus!',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('showErrorAlert', [
                'title' => 'Gagal!',
                'text'  => 'Status ini mungkin sudah digunakan.',
                'icon'  => 'error',
            ]);
        }

        $this->resetFields();
    }

    /**
     * Render
     */
    public function render()
    {
        return view(
            'features.master-data.output-target-status.status-livewire',
            [
                'statuses' => OutputTargetStatus::with('creator')
                    ->orderBy('id', 'asc')
                    ->get()
            ]
        );
    }
}



