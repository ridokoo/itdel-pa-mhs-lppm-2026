<?php

namespace App\Livewire\Master;

use Livewire\Component;

use App\Models\User;
use App\Models\Role;
use App\Models\Faculty;

use App\Helper\WithComponentNameHelper;

class FakultasLivewire extends Component
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
        $faculty = Faculty::findOrFail($id);

        $this->selected_id = $faculty->id;
        $this->name        = $faculty->name;

        $this->showForm = true;
        $this->isEdit   = true;
    }


    /**
     * ================= VALIDATION =================
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:faculties,name,' . $this->selected_id,
        ];
    }

    protected $messages = [
        'name.required' => 'Nama fakultas wajib diisi.',
        'name.unique'   => 'Nama fakultas sudah digunakan.',
        'name.max'      => 'Nama fakultas maksimal 255 karakter.',
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

            Faculty::create([
                'name'       => trim($this->name),
                'created_by' => $localUser->id,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Data fakultas berhasil ditambahkan.',
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

            $faculty = Faculty::findOrFail($this->selected_id);

            $faculty->update([
                'name' => trim($this->name)
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Data fakultas berhasil diperbarui.',
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

            Faculty::findOrFail($id)->delete();

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Data fakultas berhasil dihapus.',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Fakultas masih digunakan oleh data lain.',
                'icon'  => 'error',
            ]);

        }
    }


    /**
     * ================= RENDER =================
     */
    public function render()
    {
        return view('features.master-data.fakultas.fakultas-livewire', [

            'faculties' => Faculty::with('creator')
                ->latest()
                ->get()

        ]);
    }
}



