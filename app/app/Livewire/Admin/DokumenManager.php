<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Document;
use App\Models\DocumentCategory;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DokumenManager extends Component
{
    use WithFileUploads;

    public $viewState = 'index';
    public $docId, $name, $description, $category_id, $status = 'published';
    public $file, $old_file;

    public function render()
    {
        return view('features.admin.dokumen-manager-livewire', [
            'documents' => Document::with('category')->orderBy('created_at', 'desc')->get(),
            'categories' => DocumentCategory::all()
        ])->layout('app.admin.index');
    }

    public function showIndex() { $this->resetFields(); $this->viewState = 'index'; }
    public function showCreate() { $this->resetFields(); $this->viewState = 'create'; }

    public function resetFields() {
        $this->name = '';
        $this->description = '';
        $this->category_id = '';
        $this->file = null;
        $this->old_file = null;
        $this->docId = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255',
            'category_id' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $path = $this->file->store('documents', 'public');

        \App\Models\Document::create([
            'name' => $this->name,
            'description' => $this->description,
            'file_path' => $path,
            'document_category_id' => $this->category_id,
            'status' => $this->status,
            'created_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Dokumen baru telah berhasil diunggah.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function showEdit($id)
    {
        $d = Document::findOrFail($id);
        $this->docId = $id;
        $this->name = $d->name;
        $this->description = $d->description;
        $this->category_id = $d->document_category_id;
        $this->status = $d->status;
        $this->old_file = $d->file_path;
        $this->viewState = 'edit';
    }

    public function update()
    {
        $this->validate(['name' => 'required|max:255', 'category_id' => 'required']);

        $d = \App\Models\Document::findOrFail($this->docId);
        $path = $this->old_file;

        if ($this->file) {
            if ($this->old_file) { \Illuminate\Support\Facades\Storage::disk('public')->delete($this->old_file); }
            $path = $this->file->store('documents', 'public');
        }

        $d->update([
            'name' => $this->name,
            'description' => $this->description,
            'file_path' => $path,
            'document_category_id' => $this->category_id,
            'status' => $this->status,
            'updated_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Diperbarui!',
            'text'  => 'Data dokumen telah berhasil diperbarui.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function delete($id)
    {
        $d = Document::findOrFail($id);

        Storage::disk('public')->delete($d->file_path);
        $d->delete();

        // ✅ GANTI
        $this->dispatch('showSuccessAlert', 'Dokumen berhasil dihapus!');
    }

    public function confirmUpdate()
    {
        $this->dispatch('triggerUpdateConfirm');
    }
}



