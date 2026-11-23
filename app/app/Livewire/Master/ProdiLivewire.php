<?php

namespace App\Livewire\Master;

use Livewire\Component;

use App\Models\User;
use App\Models\Role;
use App\Models\Faculty;
use App\Models\StudyProgram;

use App\Helper\WithComponentNameHelper;

class ProdiLivewire extends Component
{
    use WithComponentNameHelper;

    public $faculty_id;
    public $name;

    public $currentUser;
    public $selected_id;

    public $isEdit = false;
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
            'faculty_id',
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
        $this->isEdit = false;
    }


    /**
     * ================= SHOW EDIT =================
     */
    public function edit($id)
    {
        $prodi = StudyProgram::findOrFail($id);

        $this->selected_id = $prodi->id;
        $this->faculty_id  = $prodi->faculty_id;
        $this->name        = $prodi->name;

        $this->showForm = true;
        $this->isEdit = true;
    }


    /**
     * ================= VALIDATION =================
     */
    protected function rules()
    {
        return [
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255',
        ];
    }

    protected $messages = [

        'faculty_id.required' => 'Fakultas wajib dipilih.',
        'faculty_id.exists'   => 'Fakultas tidak valid.',

        'name.required' => 'Nama program studi wajib diisi.',
        'name.max'      => 'Nama program studi maksimal 255 karakter.',

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

            StudyProgram::create([

                'faculty_id' => $this->faculty_id,
                'name'       => trim($this->name),
                'created_by' => $localUser->id,

            ]);

            $this->dispatch('swal:modal', [

                'title' => 'Berhasil!',
                'text'  => 'Program Studi berhasil ditambahkan.',
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

            $prodi = StudyProgram::findOrFail($this->selected_id);

            $localUser = User::where(
                'email',
                $this->currentUser->email
            )->first();

            $prodi->update([

                'faculty_id' => $this->faculty_id,
                'name'       => trim($this->name),
                'updated_by' => $localUser->id ?? null,

            ]);

            $this->dispatch('swal:modal', [

                'title' => 'Berhasil!',
                'text'  => 'Program Studi berhasil diperbarui.',
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

            StudyProgram::findOrFail($id)->delete();

            $this->dispatch('swal:modal', [

                'title' => 'Berhasil!',
                'text'  => 'Program Studi berhasil dihapus.',
                'icon'  => 'success',

            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [

                'title' => 'Gagal!',
                'text'  => 'Program Studi masih digunakan oleh data lain.',
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
            'features.master-data.prodi.prodi-livewire',
            [

                'programs' => StudyProgram::with([
                        'faculty',
                        'creator'
                    ])
                    ->latest()
                    ->get(),

                'faculties' => Faculty::orderBy('name')
                    ->get()

            ]
        );
    }
}



