<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\ReviewerStatus;
use App\Helper\WithComponentNameHelper;

class ReviewerStatusLivewire extends Component
{
    use WithComponentNameHelper;

    public $name, $selected_id;
    public $isEdit = false;

    public function resetFields()
    {
        $this->reset(['name', 'isEdit', 'selected_id']);
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->resetFields();
        $this->dispatch('showModal', id: '#modal_rev_status');
    }

    public function store()
    {
        $this->validate(['name' => 'required|max:50|unique:reviewer_status,name']);

        ReviewerStatus::create(['name' => $this->name]);

        $this->dispatch('showSuccessAlert', 'Status berhasil ditambah!');
        $this->resetFields();
        $this->dispatch('hideModal', id: '#modal_rev_status');
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $this->selected_id = $id;
        $this->name = ReviewerStatus::findOrFail($id)->name;
        $this->dispatch('showModal', id: '#modal_rev_status');
    }

    public function update()
    {
        $this->validate(['name' => 'required|max:50|unique:reviewer_status,name,' . $this->selected_id]);

        ReviewerStatus::findOrFail($this->selected_id)->update(['name' => $this->name]);

        $this->dispatch('showSuccessAlert', 'Status berhasil diperbarui!');
        $this->resetFields();
        $this->dispatch('hideModal', id: '#modal_rev_status');
    }

    public function deleteConfirm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('showModal', id: '#modal_hapus_rev_status');
    }

    public function destroy()
    {
        try {
            $status = ReviewerStatus::findOrFail($this->selected_id);

            // Cek secara manual sebelum menghapus
            if ($status->assignments()->count() > 0) {
                $this->dispatch('showErrorAlert', 'Gagal! Status ini sudah digunakan pada tugas reviewer lain.');
                $this->dispatch('hideModal', id: '#modal_hapus_rev_status');
                return;
            }

            $status->delete();
            $this->dispatch('hideModal', id: '#modal_hapus_rev_status');
            $this->dispatch('showSuccessAlert', 'Status berhasil dihapus.');
        } catch (\Exception $e) {
            // Tampilkan error database jika ada (misal constraint error)
            $this->dispatch('showErrorAlert', 'Database menolak penghapusan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('features.master-data.reviewer-status.status-livewire', [
            'statuses' => ReviewerStatus::orderBy('id', 'asc')->get()
        ]);
    }
}



