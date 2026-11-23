<?php

namespace App\Livewire\Master;

use Livewire\Component;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\SchemeGroup;

use App\Helper\WithComponentNameHelper;

class SchemeGroupLivewire extends Component
{
    use WithComponentNameHelper;

    public $category_id;
    public $name;
    public $currentUser;
    public $selected_id;

    public $isEdit   = false;
    public $showForm = false;


    /**
     * ================= MOUNT =================
     */
    public function mount()
    {
        $this->currentUser = request()->auth;
    }


    /**
     * ================= RESET =================
     */
    public function resetFields()
    {
        $this->reset([
            'category_id',
            'name',
            'selected_id',
            'isEdit',
            'showForm'
        ]);

        $this->resetErrorBag();
        $this->resetValidation();
    }


    /**
     * ================= SHOW CREATE =================
     */
    public function create()
    {
        $this->resetFields();

        $this->showForm = true;
        $this->isEdit   = false;
    }


    /**
     * ================= SHOW EDIT =================
     */
    public function edit($id)
    {
        $group = SchemeGroup::findOrFail($id);

        $this->selected_id = $group->id;
        $this->category_id = $group->category_id;
        $this->name        = $group->name;

        $this->showForm = true;
        $this->isEdit   = true;
    }


    /**
     * ================= VALIDATION =================
     */
    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'category_id.required' => 'Kategori wajib dipilih.',
        'category_id.exists'   => 'Kategori tidak valid.',

        'name.required'        => 'Nama kelompok skema wajib diisi.',
        'name.max'             => 'Nama maksimal 255 karakter.',
    ];


    /**
     * ================= STORE =================
     */
    public function store()
    {
        $this->validate();

        try {

            $role = Role::where('name', 'operator')->first();

            $localUser = User::firstOrCreate(

                [
                    'email' => $this->currentUser->email
                ],

                [
                    'name'      => $this->currentUser->name,
                    'cis_id'    => $this->currentUser->id,
                    'role_id'   => $role->id ?? null,
                    'is_active' => true,
                ]

            );

            SchemeGroup::create([
                'category_id' => $this->category_id,
                'name'        => trim($this->name),
                'created_by'  => $localUser->id,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Kelompok skema berhasil ditambahkan.',
                'icon'  => 'success',
            ]);

            $this->resetFields();

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Terjadi kesalahan saat menyimpan data.',
                'icon'  => 'error',
            ]);

        }
    }


    /**
     * ================= CONFIRM UPDATE =================
     */
    public function confirmUpdate()
    {
        $this->dispatch('triggerUpdateConfirm');
    }


    /**
     * ================= UPDATE =================
     */
    public function update()
    {
        $this->validate();

        try {

            $group = SchemeGroup::findOrFail($this->selected_id);

            $localUser = User::where(
                'email',
                $this->currentUser->email
            )->first();

            $group->update([
                'category_id' => $this->category_id,
                'name'        => trim($this->name),
                'updated_by'  => $localUser->id ?? null,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Kelompok skema berhasil diperbarui.',
                'icon'  => 'success',
            ]);

            $this->resetFields();

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Terjadi kesalahan saat memperbarui data.',
                'icon'  => 'error',
            ]);

        }
    }


    /**
     * ================= DELETE =================
     */
    public function delete($id)
    {
        try {

            SchemeGroup::findOrFail($id)->delete();

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Kelompok skema berhasil dihapus.',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Kelompok skema masih digunakan oleh data lain.',
                'icon'  => 'error',
            ]);

        }
    }


    /**
     * ================= RENDER =================
     */
    public function render()
    {
        return view(
            'features.master-data.scheme-group.scheme-group-livewire',
            [

                'groups' => SchemeGroup::with([
                        'category',
                        'creator'
                    ])
                    ->latest()
                    ->get(),

                'categories' => Category::where(
                        'is_active',
                        true
                    )->get()

            ]
        );
    }
}



