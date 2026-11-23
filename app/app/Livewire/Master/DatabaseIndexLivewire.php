<?php

namespace App\Livewire\Master;

use Livewire\Component;

use App\Models\User;
use App\Models\Role;
use App\Models\DatabaseIndex;
use Illuminate\Validation\Rule;

use App\Helper\WithComponentNameHelper;

class DatabaseIndexLivewire extends Component
{
    use WithComponentNameHelper;

    public $name;
    public $base_url;
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
            'base_url',
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
        $index = DatabaseIndex::findOrFail($id);

        $this->selected_id = $index->id;
        $this->name        = $index->name;
        $this->base_url    = $index->base_url;

        $this->showForm = true;
        $this->isEdit   = true;
    }


    /**
     * ================= VALIDATION =================
     */
   protected function rules()
{
    return [
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('database_indexes', 'name')
                ->ignore($this->selected_id),
        ],
        'base_url' => 'nullable|url|max:255',
    ];
}

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

            DatabaseIndex::create([
                'name'       => trim($this->name),
                'base_url'   => trim($this->base_url),
                'created_by' => $localUser->id,
                'is_active'  => 1,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Data indeks berhasil ditambahkan.',
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

            $index = DatabaseIndex::findOrFail($this->selected_id);

            $localUser = User::where(
                'email',
                $this->currentUser->email
            )->first();

            $index->update([
                'name'       => trim($this->name),
                'base_url'   => trim($this->base_url),
                'updated_by' => $localUser->id ?? null,
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Data indeks berhasil diperbarui.',
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

            $index = DatabaseIndex::findOrFail($id);

            $index->update([
                'is_active' => 0
            ]);

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Data indeks berhasil dihapus.',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Data indeks gagal dihapus.',
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
            'features.master-data.database-index.database-index-livewire',
            [

                'indexes' => DatabaseIndex::with('creator')
                    ->where('is_active', 1)
                    ->latest()
                    ->get()

            ]
        );
    }
}



