<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Helper\WithComponentNameHelper;

class CategoryLivewire extends Component
{
    use WithComponentNameHelper;

    public $name;
    public $currentUser;
    public $selected_id;

    public $isEdit = false;
    public $showForm = false;

    public function mount()
    {
        $this->currentUser = request()->auth;
    }

    public function resetFields()
    {
        $this->reset([
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
            'name' => 'required|max:255'
        ]);

        $role = Role::where('name', 'operator')->first();

        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name' => $this->currentUser->name,
                'cis_id' => $this->currentUser->id,
                'role_id' => $role->id ?? null,
                'is_active' => true
            ]
        );

        Category::create([
            'name' => $this->name,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Kategori berhasil ditambahkan!',
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

        $data = Category::findOrFail($id);

        $this->name = $data->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255'
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email
        )->first();

        Category::findOrFail($this->selected_id)->update([
            'name' => $this->name,
            'updated_by' => $localUser->id ?? null,
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Kategori berhasil diperbarui!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= DELETE =================
    public function delete($id)
    {
        try {

            Category::findOrFail($id)->delete();

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Kategori berhasil dihapus!',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Kategori masih digunakan oleh data lain.',
                'icon'  => 'error',
            ]);

        }
    }

    // ================= RENDER =================
    public function render()
    {
        return view('features.master-data.category.category-livewire', [
            'categories' => Category::with('creator')
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }
}



