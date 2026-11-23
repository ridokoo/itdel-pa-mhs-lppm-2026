<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Gallery;
use App\Models\GalleryItem;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GalleryManager extends Component
{
    use WithFileUploads;

    public $viewState = 'index';
    public $galleryId, $title, $description, $event_date, $status = 'published', $cover_image, $old_cover;

    public $photos = [];

    public function render()
    {
        return view('features.admin.gallery-manager-livewire', [
            'galleries' => Gallery::withCount('items')->orderBy('event_date', 'desc')->get(),
            'currentGallery' => $this->galleryId ? Gallery::with('items')->find($this->galleryId) : null
        ])->layout('app.admin.index');
    }

    public function showIndex() {
        $this->resetFields();
        $this->viewState = 'index';
    }

    public function showCreate() {
        $this->resetFields();
        $this->viewState = 'create';
    }

    public function showEdit($id)
    {
        $g = Gallery::findOrFail($id);
        $this->galleryId = $id;
        $this->title = $g->title;
        $this->description = $g->description;
        $this->event_date = $g->event_date;
        $this->status = $g->status;
        $this->old_cover = $g->cover_image;
        $this->viewState = 'edit';
    }

    public function resetFields()
    {
        $this->reset([
            'title','description','cover_image','old_cover',
            'galleryId','photos'
        ]);
        $this->status = 'published';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:5',
            'cover_image' => 'required|image|max:2048',
            'event_date' => 'required|date',
        ]);

        $path = $this->cover_image->store('galleries/covers', 'public');

        Gallery::create([
            'title' => $this->title,
            'description' => $this->description,
            'event_date' => $this->event_date,
            'cover_image' => $path,
            'status' => $this->status,
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Album galeri baru telah dibuat.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function confirmUpdate()
    {
        $this->dispatch('triggerUpdateConfirm');
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|min:5',
            'cover_image' => 'nullable|image|max:2048',
            'event_date' => 'required|date',
        ]);

        $g = Gallery::findOrFail($this->galleryId);
        $path = $this->old_cover;

        if ($this->cover_image) {
            if ($this->old_cover) {
                Storage::disk('public')->delete($this->old_cover);
            }
            $path = $this->cover_image->store('galleries/covers', 'public');
        }

        $g->update([
            'title' => $this->title,
            'description' => $this->description,
            'event_date' => $this->event_date,
            'cover_image' => $path,
            'status' => $this->status,
            'updated_by' => Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Diperbarui!',
            'text'  => 'Data album berhasil diperbarui.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function delete($id)
    {
        $g = Gallery::findOrFail($id);

        if ($g->cover_image) {
            Storage::disk('public')->delete($g->cover_image);
        }

        foreach ($g->items as $item) {
            Storage::disk('public')->delete($item->file_path);
        }

        $g->delete();

        $this->dispatch('swal:modal', [
            'title' => 'Dihapus!',
            'text'  => 'Album dan seluruh isinya telah dihapus.',
            'icon'  => 'success'
        ]);
    }

    public function showManageItems($id)
    {
        $this->galleryId = $id;
        $this->viewState = 'manage_items';
    }

    public function addPhotos()
    {
        $this->validate([
            'photos.*' => 'image|max:3072'
        ]);

        foreach ($this->photos as $photo) {
            $path = $photo->store('galleries/items', 'public');

            GalleryItem::create([
                'gallery_id' => $this->galleryId,
                'file_path' => $path,
                'media_type' => 'image',
                'created_by' => Auth::id(),
            ]);
        }

        $this->photos = [];
        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Foto-foto telah ditambahkan ke album.',
            'icon'  => 'success'
        ]);
    }

    public function deleteItem($id)
    {
        $item = GalleryItem::findOrFail($id);
        Storage::disk('public')->delete($item->file_path);
        $item->delete();

        $this->dispatch('swal:modal', [
            'title' => 'Terhapus!',
            'text'  => 'Foto telah dihapus dari album.',
            'icon'  => 'success'
        ]);
    }

}



