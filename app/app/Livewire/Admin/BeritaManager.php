<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\News;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaManager extends Component
{
    use WithFileUploads;

    public $allNews, $newsId;
    public $title, $content, $event_date, $image, $old_image;
    public $status = 'published';

    public $viewState = 'index';

    public function render()
    {
        $this->allNews = News::orderBy('event_date', 'desc')->get();
        return view('features.admin.berita-manager-livewire')
            ->layout('app.admin.index');
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

    public function showEdit($id)
    {
        $n = News::findOrFail($id);

        $this->newsId     = $id;
        $this->title      = $n->title;
        $this->content    = $n->content;
        $this->event_date = $n->event_date;
        $this->status     = $n->status;
        $this->old_image  = $n->image;
        $this->image      = null;

        $this->viewState = 'edit';
    }

    public function resetFields()
    {
        $this->title = '';
        $this->content = '';
        $this->event_date = '';
        $this->image = null;
        $this->old_image = null;
        $this->newsId = null;
        $this->status = 'published';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'event_date' => 'required|date',
            // Tambahkan mimes untuk memastikan file terbaca dengan benar meski landscape
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $this->image
            ? $this->image->store('news', 'public')
            : null;

        News::create([
            'title'      => $this->title,
            'content'    => $this->content,
            'event_date' => $this->event_date,
            'image'      => $imagePath,
            'status'     => $this->status,
            'created_by' => Auth::id(),
        ]);

        $this->showIndex();

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Berita baru telah berhasil diterbitkan.',
            'icon'  => 'success'
        ]);
    }

    // Fungsi Jembatan untuk SweetAlert
    public function confirmUpdate()
    {
        // Validasi dilakukan di sini sebelum Alert muncul
        $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->dispatch('triggerUpdateConfirm');
    }

    public function update()
    {
        // Tetap validasi di dalam update untuk keamanan
        $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $n = News::findOrFail($this->newsId);
        $imagePath = $this->old_image;

        if ($this->image) {
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                Storage::disk('public')->delete($this->old_image);
            }
            $imagePath = $this->image->store('news', 'public');
        }

        $n->update([
            'title'      => $this->title,
            'content'    => $this->content,
            'event_date' => $this->event_date,
            'image'      => $imagePath,
            'status'     => $this->status,
            'updated_by' => Auth::id(),
        ]);

        $this->showIndex();

        $this->dispatch('swal:modal', [
            'title' => 'Diperbarui!',
            'text'  => 'Data berita telah berhasil diperbarui.',
            'icon'  => 'success'
        ]);
    }

    public function delete($id)
    {
        $n = News::findOrFail($id);
        if ($n->image && Storage::disk('public')->exists($n->image)) {
            Storage::disk('public')->delete($n->image);
        }
        $n->delete();

        $this->dispatch('swal:modal', [
            'title' => 'Dihapus!',
            'text'  => 'Berita telah dihapus.',
            'icon'  => 'success'
        ]);
    }
}



