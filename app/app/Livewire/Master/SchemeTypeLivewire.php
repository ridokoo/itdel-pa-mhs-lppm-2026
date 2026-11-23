<?php

namespace App\Livewire\Master;

use Livewire\Component;

use App\Models\User;
use App\Models\Role;
use App\Models\SchemeGroup;
use App\Models\SchemeType;

use App\Helper\WithComponentNameHelper;

class SchemeTypeLivewire extends Component
{
    use WithComponentNameHelper;

    public $scheme_group_id;
    public $name;
    public $tkt_min = 1;
    public $tkt_max = 9;

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
            'scheme_group_id',
            'name',
            'selected_id',
            'isEdit',
            'showForm'
        ]);

        $this->tkt_min = 1;
        $this->tkt_max = 9;

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
        $type = SchemeType::findOrFail($id);

        $this->selected_id     = $type->id;
        $this->scheme_group_id = $type->scheme_group_id;
        $this->name            = $type->name;
        $this->tkt_min         = $type->tkt_min;
        $this->tkt_max         = $type->tkt_max;

        $this->showForm = true;
        $this->isEdit   = true;
    }


    /**
     * ================= VALIDATION =================
     */
    protected function rules()
    {
        return [
            'scheme_group_id' => 'required|exists:scheme_groups,id',
            'name'            => 'required|string|max:255',
            'tkt_min'         => 'required|integer|min:1|max:9',
            'tkt_max'         => 'required|integer|min:1|max:9|gte:tkt_min',
        ];
    }

    protected $messages = [

        'scheme_group_id.required' => 'Kelompok skema wajib dipilih.',
        'scheme_group_id.exists'   => 'Kelompok skema tidak valid.',

        'name.required' => 'Nama tipe skema wajib diisi.',
        'name.max'      => 'Nama tipe skema maksimal 255 karakter.',

        'tkt_min.required' => 'TKT minimum wajib diisi.',
        'tkt_min.min'      => 'TKT minimum minimal 1.',
        'tkt_min.max'      => 'TKT minimum maksimal 9.',

        'tkt_max.required' => 'TKT maximum wajib diisi.',
        'tkt_max.gte'      => 'TKT maximum harus lebih besar atau sama dengan TKT minimum.',
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

            SchemeType::create([

                'scheme_group_id' => $this->scheme_group_id,
                'name'            => trim($this->name),
                'tkt_min'         => $this->tkt_min,
                'tkt_max'         => $this->tkt_max,
                'created_by'      => $localUser->id,

            ]);

            $this->dispatch('swal:modal', [

                'title' => 'Berhasil!',
                'text'  => 'Tipe skema berhasil ditambahkan.',
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

            $type = SchemeType::findOrFail($this->selected_id);

            $localUser = User::where(
                'email',
                $this->currentUser->email
            )->first();

            $type->update([

                'scheme_group_id' => $this->scheme_group_id,
                'name'            => trim($this->name),
                'tkt_min'         => $this->tkt_min,
                'tkt_max'         => $this->tkt_max,
                'updated_by'      => $localUser->id ?? null,

            ]);

            $this->dispatch('swal:modal', [

                'title' => 'Berhasil!',
                'text'  => 'Tipe skema berhasil diperbarui.',
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

            SchemeType::findOrFail($id)->delete();

            $this->dispatch('swal:modal', [

                'title' => 'Berhasil!',
                'text'  => 'Tipe skema berhasil dihapus.',
                'icon'  => 'success',

            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [

                'title' => 'Gagal!',
                'text'  => 'Data masih digunakan.',
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
            'features.master-data.scheme-type.scheme-type-livewire',
            [

                'types' => SchemeType::with([
                        'schemeGroup',
                        'creator'
                    ])
                    ->latest()
                    ->get(),

                'groups' => SchemeGroup::where(
                        'is_active',
                        true
                    )->get()

            ]
        );
    }
}



