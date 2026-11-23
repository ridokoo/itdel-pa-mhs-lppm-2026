<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\ExpenseCategory;
use App\Helper\WithComponentNameHelper;

class ExpenseCategoryLivewire extends Component
{
    use WithComponentNameHelper;

    public $name;
    public $description;
    public $percentage_min = 0;
    public $percentage_max = 100;
    public $display_order = 1;

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
            'description',
            'percentage_min',
            'percentage_max',
            'display_order',
            'isEdit',
            'selected_id',
            'showForm',
            'showDeleteConfirm'
        ]);

        $this->percentage_min = 0;
        $this->percentage_max = 100;
        $this->display_order = 1;

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
            'name' => 'required|max:255',
            'percentage_min' => 'required|numeric|min:0|max:100',
            'percentage_max' => 'required|numeric|min:0|max:100|gte:percentage_min',
            'display_order' => 'required|integer|min:1'
        ]);

        $role = Role::where('name', 'operator')->first();

        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name' => $this->currentUser->name ?? 'User',
                'cis_id' => $this->currentUser->id ?? null,
                'role_id' => $role->id ?? null,
                'is_active' => true
            ]
        );

        ExpenseCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'percentage_min' => $this->percentage_min,
            'percentage_max' => $this->percentage_max,
            'display_order' => $this->display_order,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch('showSuccessAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Kategori biaya berhasil ditambahkan!'
        ]);

        $this->resetFields();
    }

    public function edit($id)
    {
        $this->resetErrorBag();

        $this->isEdit = true;
        $this->selected_id = $id;
        $this->showForm = true;
        $this->showDeleteConfirm = false;

        $data = ExpenseCategory::findOrFail($id);

        $this->name = $data->name;
        $this->description = $data->description;
        $this->percentage_min = $data->percentage_min;
        $this->percentage_max = $data->percentage_max;
        $this->display_order = $data->display_order;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255',
            'percentage_min' => 'required|numeric|min:0|max:100',
            'percentage_max' => 'required|numeric|min:0|max:100|gte:percentage_min',
            'display_order' => 'required|integer|min:1'
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        ExpenseCategory::findOrFail($this->selected_id)->update([
            'name' => $this->name,
            'description' => $this->description,
            'percentage_min' => $this->percentage_min,
            'percentage_max' => $this->percentage_max,
            'display_order' => $this->display_order,
            'updated_by' => $localUser->id ?? null,
        ]);

        $this->dispatch('showSuccessAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Kategori biaya berhasil diperbarui!'
        ]);

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

            ExpenseCategory::findOrFail($this->selected_id)->delete();

            $this->dispatch('showSuccessAlert', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Kategori biaya berhasil dihapus!'
            ]);

        } catch (\Exception $e) {

            $this->dispatch('showErrorAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Gagal menghapus data!'
            ]);
        }

        $this->resetFields();
    }

    public function render()
    {
        return view(
            'features.master-data.expense-category.category-livewire',
            [
                'categories' => ExpenseCategory::with('creator')
                    ->orderBy('display_order', 'asc')
                    ->get()
            ]
        );
    }
}



