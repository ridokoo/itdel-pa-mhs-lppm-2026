<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\ProposalPeriod;
use App\Helper\WithComponentNameHelper;

class ProposalPeriodLivewire extends Component
{
    use WithComponentNameHelper;

    public $year;
    public $name;
    public $start_date;
    public $end_date;
    public $is_active = true;

    public $currentUser;
    public $selected_id;

    public $isEdit = false;
    public $showForm = false;

    public function mount()
    {
        $this->currentUser = request()->auth ?? auth()->user();
        $this->year = date('Y');
    }

    public function resetFields()
    {
        $this->reset([
            'year',
            'name',
            'start_date',
            'end_date',
            'is_active',
            'isEdit',
            'selected_id',
            'showForm'
        ]);

        $this->year = date('Y');
        $this->is_active = true;

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
            'year'       => 'required|numeric|min:2000',
            'name'       => 'required|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        if (!$this->currentUser) return;

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

        ProposalPeriod::create([
            'year'       => $this->year,
            'name'       => $this->name,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'is_active'  => $this->is_active,
            'created_by' => $localUser->id,
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Periode proposal berhasil ditambahkan!',
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

        $data = ProposalPeriod::findOrFail($id);

        $this->year       = $data->year;
        $this->name       = $data->name;
        $this->start_date = optional($data->start_date)->format('Y-m-d');
        $this->end_date   = optional($data->end_date)->format('Y-m-d');
        $this->is_active  = $data->is_active;
    }

    public function update()
    {
        $this->validate([
            'year'       => 'required|numeric|min:2000',
            'name'       => 'required|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $localUser = User::where(
            'email',
            $this->currentUser->email ?? null
        )->first();

        ProposalPeriod::findOrFail($this->selected_id)->update([
            'year'       => $this->year,
            'name'       => $this->name,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'is_active'  => $this->is_active,
            'updated_by' => $localUser->id ?? null,
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Periode proposal berhasil diperbarui!',
            'icon'  => 'success',
        ]);

        $this->resetFields();
    }

    // ================= DELETE =================

    public function delete($id)
    {
        try {

            $period = ProposalPeriod::findOrFail($id);

            if ($period->proposals()->count() > 0) {

                $this->dispatch('swal:modal', [
                    'title' => 'Gagal!',
                    'text'  => 'Periode ini sudah memiliki proposal.',
                    'icon'  => 'error',
                ]);

                return;
            }

            $period->delete();

            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text'  => 'Periode proposal berhasil dihapus!',
                'icon'  => 'success',
            ]);

        } catch (\Exception $e) {

            $this->dispatch('swal:modal', [
                'title' => 'Gagal!',
                'text'  => 'Terjadi kesalahan saat menghapus data.',
                'icon'  => 'error',
            ]);

        }
    }

    // ================= RENDER =================

    public function render()
    {
        return view('features.master-data.periods.period-livewire', [
            'periods' => ProposalPeriod::with('creator')
                ->latest()
                ->get()
        ]);
    }
}



