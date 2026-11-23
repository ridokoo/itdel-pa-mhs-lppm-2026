<?php

namespace App\Livewire\Operator;

use Livewire\Component;
use App\Models\StatusFinalUsulan;
use App\Models\User;

class StatusFinalUsulanLivewire extends Component
{
    public $name;
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
            'isEdit',
            'selected_id',
            'showForm',
            'showDeleteConfirm',
        ]);

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
            'name' => 'required|max:255|unique:proposal_status,name',
        ]);

        $localUser = User::where('email', $this->currentUser->email ?? null)->first();

        StatusFinalUsulan::create([
            'name'       => $this->name,
            'created_by' => $localUser->id ?? auth()->id(),
        ]);

        session()->flash('success', 'Status final usulan berhasil ditambahkan!');

        $this->resetFields();
    }

    public function edit($id)
    {
        $this->resetErrorBag();

        $this->isEdit             = true;
        $this->selected_id        = $id;
        $this->showForm           = true;
        $this->showDeleteConfirm  = false;

        $data = StatusFinalUsulan::findOrFail($id);

        $this->name = $data->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|unique:proposal_status,name,' . $this->selected_id,
        ]);

        $localUser = User::where('email', $this->currentUser->email ?? null)->first();

        StatusFinalUsulan::findOrFail($this->selected_id)->update([
            'name'       => $this->name,
            'updated_by' => $localUser->id ?? auth()->id(),
        ]);

        session()->flash('success', 'Status final usulan berhasil diperbarui!');

        $this->resetFields();
    }

    public function deleteConfirm($id)
    {
        $this->selected_id       = $id;
        $this->showDeleteConfirm = true;
        $this->showForm          = false;
    }

    public function destroy()
    {
        try {
            StatusFinalUsulan::findOrFail($this->selected_id)->delete();

            session()->flash('success', 'Status final usulan berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus! Status ini sudah digunakan dalam data lain.');
        }

        $this->resetFields();
    }

    public function render()
    {
        return view(
            'features.operator.status-final-usulan.status-final-usulan-livewire',
            [
                'statuses' => StatusFinalUsulan::with('creator')
                    ->orderBy('id', 'asc')
                    ->get(),
            ]
        );
    }
}



