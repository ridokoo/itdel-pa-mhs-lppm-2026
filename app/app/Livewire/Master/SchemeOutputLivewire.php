<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\SchemeType;
use App\Models\OutputCategoryDetail;
use App\Helper\WithComponentNameHelper;

class SchemeOutputLivewire extends Component
{
    use WithComponentNameHelper;

    public $scheme_type_id, $name;
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
            'scheme_type_id',
            'name',
            'isEdit',
            'selected_id',
            'showForm'
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
            'scheme_type_id' => 'required|exists:scheme_types,id',
            'name'           => 'required|max:255'
        ]);

        if (!$this->currentUser) {
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

        OutputCategoryDetail::create([
            'scheme_type_id' => $this->scheme_type_id,
            'name'           => $this->name,
            'created_by'     => $localUser->id,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Target luaran berhasil ditambahkan!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $this->resetErrorBag();

        $data = OutputCategoryDetail::findOrFail($id);

        $this->selected_id    = $data->id;
        $this->scheme_type_id = $data->scheme_type_id;
        $this->name           = $data->name;

        $this->isEdit = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'scheme_type_id' => 'required|exists:scheme_types,id',
            'name'           => 'required|max:255'
        ]);

        $data = OutputCategoryDetail::findOrFail($this->selected_id);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        $data->update([
            'scheme_type_id' => $this->scheme_type_id,
            'name'           => $this->name,
            'updated_by'     => $localUser->id ?? null,
        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Target luaran berhasil diperbarui!',
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

            OutputCategoryDetail::findOrFail(
                $this->selected_id
            )->delete();

            $this->dispatch('showSuccessAlert', [
                'title' => 'Berhasil!',
                'text'  => 'Target luaran berhasil dihapus!',
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
            'features.master-data.scheme-output.output-livewire',
            [

                'outputs' => OutputCategoryDetail::with([
                        'schemeType',
                        'creator'
                    ])
                    ->latest()
                    ->get(),

                'schemeTypes' => SchemeType::where(
                        'is_active',
                        true
                    )->get()
            ]
        );
    }
}



