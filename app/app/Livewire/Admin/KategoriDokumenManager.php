<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DocumentCategory;
use Illuminate\Support\Facades\Auth;

class KategoriDokumenManager extends Component
{
    public $viewState = 'index';
    public $categoryId, $name;

    public function render()
    {
        return view('features.admin.kategori-dokumen-manager-livewire', [
            'categories' => DocumentCategory::orderBy('name', 'asc')->get()
        ])->layout('app.admin.index');
    }

    public function showIndex()
    {
        $this->resetFields();
        $this->viewState = 'index';
    }

    public function showCreate()
    {
        $this->resetFields();
        $this->viewState = 'create';
    }

    public function resetFields()
    {
        $this->reset(['name', 'categoryId']);
        $this->resetErrorBag();
    }

    // ================= CREATE =================
    public function store()
    {
        $this->validate([
            'name' => 'required|min:3|unique:document_categories,name',
        ]);

        DocumentCategory::create([
            'name' => $this->name,
            'is_active' => true,
            'created_by' => Auth::id() // Tambahkan agar konsisten mencatat pembuat
        ]);

        // Menampilkan Alert Sukses Tambah
        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Kategori dokumen baru telah dibuat.',
            'icon'  => 'success'
        ]);

        session()->flash('success', 'Kategori Dokumen berhasil dibuat!');
        $this->showIndex();
    }

    // ================= EDIT =================
    public function showEdit($id)
    {
        $data = DocumentCategory::findOrFail($id);

        $this->categoryId = $id;
        $this->name = $data->name;
        $this->viewState = 'edit';
    }

    // ================= UPDATE =================
    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|unique:document_categories,name,' . $this->categoryId,
        ]);

        $data = DocumentCategory::findOrFail($this->categoryId);

        $data->update([
            'name' => $this->name,
            'updated_by' => Auth::id() // Tambahkan pencatatan pengubah
        ]);

        // Menampilkan Alert Sukses Update
        $this->dispatch('swal:modal', [
            'title' => 'Diperbarui!',
            'text'  => 'Nama kategori berhasil diperbarui.',
            'icon'  => 'success'
        ]);

        session()->flash('success', 'Kategori Dokumen berhasil diperbarui!');
        $this->showIndex();
    }

    // ================= DELETE =================
    public function delete($id)
    {
        $category = DocumentCategory::findOrFail($id);

        // ❗ Cegah hapus jika masih dipakai
        if ($category->documents()->count() > 0) {
            $this->dispatch('swal:modal', [
                'title' => 'Gagal Hapus!',
                'text'  => 'Kategori ini tidak bisa dihapus karena masih berisi dokumen.',
                'icon'  => 'error'
            ]);
            session()->flash('error', 'Kategori tidak bisa dihapus karena masih berisi dokumen!');
            return;
        }

        $category->delete();

        // Menampilkan Alert Sukses Hapus
        $this->dispatch('swal:modal', [
            'title' => 'Dihapus!',
            'text'  => 'Kategori telah berhasil dihapus.',
            'icon'  => 'success'
        ]);

        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Memicu konfirmasi update di sisi JavaScript
     */
    public function confirmUpdate()
    {
        $this->dispatch('triggerUpdateConfirm');
    }
}



