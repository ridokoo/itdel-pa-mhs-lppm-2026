<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\SchemeType;
use App\Models\CriteriaCategoryDetail;
use App\Models\FunctionalPosition;
use App\Models\Role;
use App\Helper\WithComponentNameHelper;

class SchemeCriteriaLivewire extends Component
{
    use WithComponentNameHelper;

    public $currentUser;
    public $selected_id;

    public $isEdit = false;
    public $showForm = false;

    public $scheme_type_id;
    public $min_budget;
    public $max_budget;
    public $min_members;
    public $max_members;
    public $min_functional_position_id;

    public $allow_s2 = true;
    public $allow_s3 = true;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
    }

    // ================= RESET =================
    public function resetFields()
    {
        $this->reset([
            'scheme_type_id',
            'min_budget',
            'max_budget',
            'min_members',
            'max_members',
            'min_functional_position_id',
            'allow_s2',
            'allow_s3',
            'isEdit',
            'selected_id',
            'showForm'
        ]);

        $this->allow_s2 = true;
        $this->allow_s3 = true;

        $this->resetErrorBag();
    }

    // ================= CREATE =================
    public function create()
    {
        $this->resetFields();

        $this->showForm = true;
        $this->isEdit = false;
    }

    // ================= STORE =================
    public function store()
    {
        $this->validate([
            'scheme_type_id'
                => 'required|unique:criteria_category_details,scheme_type_id',

            'max_budget'
                => 'required',

            'max_members'
                => 'required|integer',
        ]);

        $role = Role::where('name', 'operator')->first();

        $localUser = User::firstOrCreate(
            ['email' => $this->currentUser->email],
            [
                'name'      => $this->currentUser->name,
                'cis_id'    => $this->currentUser->id,
                'role_id'   => $role->id ?? null,
                'is_active' => true
            ]
        );

        CriteriaCategoryDetail::create([

            'scheme_type_id' => $this->scheme_type_id,

            'min_budget' => (float) str_replace(
                '.',
                '',
                $this->min_budget ?: 0
            ),

            'max_budget' => (float) str_replace(
                '.',
                '',
                $this->max_budget
            ),

            'min_members' => $this->min_members ?: 1,

            'max_members' => $this->max_members,

            'min_functional_position_id'
                => $this->min_functional_position_id,

            'allow_s2' => $this->allow_s2,
            'allow_s3' => $this->allow_s3,

            'created_by' => $localUser->id ?? null,

        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Aturan skema berhasil disimpan!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $this->resetFields();

        $data = CriteriaCategoryDetail::findOrFail($id);

        $this->selected_id = $data->id;

        $this->scheme_type_id = $data->scheme_type_id;

        $this->min_budget = number_format(
            $data->min_budget,
            0,
            ',',
            '.'
        );

        $this->max_budget = number_format(
            $data->max_budget,
            0,
            ',',
            '.'
        );

        $this->min_members = $data->min_members;
        $this->max_members = $data->max_members;

        $this->min_functional_position_id
            = $data->min_functional_position_id;

        $this->allow_s2 = $data->allow_s2;
        $this->allow_s3 = $data->allow_s3;

        $this->isEdit = true;
        $this->showForm = true;
    }

    // ================= UPDATE =================
    public function update()
    {
        $this->validate([
            'scheme_type_id'
                => 'required|unique:criteria_category_details,scheme_type_id,' . $this->selected_id,

            'max_budget'
                => 'required',

            'max_members'
                => 'required|integer',
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        CriteriaCategoryDetail::findOrFail(
            $this->selected_id
        )->update([

            'scheme_type_id' => $this->scheme_type_id,

            'min_budget' => (float) str_replace(
                '.',
                '',
                $this->min_budget ?: 0
            ),

            'max_budget' => (float) str_replace(
                '.',
                '',
                $this->max_budget
            ),

            'min_members' => $this->min_members ?: 1,

            'max_members' => $this->max_members,

            'min_functional_position_id'
                => $this->min_functional_position_id,

            'allow_s2' => $this->allow_s2,
            'allow_s3' => $this->allow_s3,

            'updated_by' => $localUser->id ?? null,

        ]);

        $this->dispatch('showSuccessAlert', [
            'title' => 'Berhasil!',
            'text'  => 'Aturan skema berhasil diperbarui!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= DELETE =================
    public function deleteConfirm($id)
    {
        $this->selected_id = $id;

        $this->destroy();
    }

    public function destroy()
    {
        try {

            CriteriaCategoryDetail::findOrFail(
                $this->selected_id
            )->delete();

            $this->dispatch('showSuccessAlert', [
                'title' => 'Berhasil!',
                'text'  => 'Aturan skema berhasil dihapus!',
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
            'features.master-data.scheme-criteria.criteria-livewire',
            [

                'criteria' => CriteriaCategoryDetail::with([
                    'schemeType',
                    'functionalPosition',
                    'creator'
                ])
                ->latest()
                ->get(),

                'schemeTypes' => SchemeType::orderBy('name')->get(),

                'functionalPositions'
                    => FunctionalPosition::orderBy('name')->get(),

                'types' => SchemeType::all(),

            ]
        );
    }
}



