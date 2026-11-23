<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\FunctionalPosition;
use App\Helper\WithComponentNameHelper;

class JabatanFungsionalLivewire extends Component
{
    use WithComponentNameHelper;

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
    public function showCreate()
    {
        $this->resetFields();

        $this->showForm = true;
        $this->isEdit   = false;
    }


    /**
     * ================= SHOW EDIT =================
     */
    public function showEdit($id)
    {
        $position = FunctionalPosition::findOrFail($id);

        $this->selected_id = $position->id;
        $this->name        = $position->name;

        $this->showForm = true;
        $this->isEdit   = true;
    }


    /**
     * ================= VALIDATION =================
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:functional_positions,name,' . $this->selected_id,
        ];
    }

    protected $messages = [
        'name.required' => 'Nama jabatan wajib diisi.',
        'name.unique'   => 'Nama jabatan sudah digunakan.',
        'name.max'      => 'Nama jabatan maksimal 255 karakter.',
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

            FunctionalPosition::create([
                'name'       => trim($this->name),
                'created_by' => $localUser->id,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Jabatan fungsional berhasil ditambahkan.',
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

            $position = FunctionalPosition::findOrFail($this->selected_id);

            $localUser = User::where(
                'email',
                $this->currentUser->email
            )->first();

            $position->update([
                'name'       => trim($this->name),
                'updated_by' => $localUser->id ?? null,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Jabatan fungsional berhasil diperbarui.',
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

            FunctionalPosition::findOrFail($id)->delete();

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Jabatan fungsional berhasil dihapus.',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Jabatan masih digunakan oleh data lain.',
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
            'features.master-data.jabatan-fungsional.jabatan-fungsional-livewire',
            [

                'positions' => FunctionalPosition::with('creator')
                    ->latest()
                    ->get()

            ]
        );
    }
}



