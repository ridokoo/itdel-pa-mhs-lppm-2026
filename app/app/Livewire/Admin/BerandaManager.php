<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Profile;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BerandaManager extends Component
{
    use WithFileUploads;

    public $profiles, $profileId;
    public $title, $content, $content_type, $image, $old_image;
    public $display_order = 1, $status = 'published';

    public $viewState = 'index';

    public function render()
    {
        $this->profiles = Profile::orderBy('display_order', 'asc')->get();
        return view('features.admin.beranda-manager-livewire')
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

    public function resetFields()
    {
        $this->title = '';
        $this->content = '';
        $this->content_type = '';
        $this->image = null;
        $this->old_image = null;
        $this->profileId = null;
        $this->display_order = 1;
        $this->status = 'published';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'content_type' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        $imageName = $this->image ? $this->image->store('profiles', 'public') : null;

        Profile::create([
            'title' => $this->title,
            'content' => $this->content,
            'content_type' => $this->content_type,
            'image' => $imageName,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'created_by' => Auth::id(),
        ]);

        // Kirim sinyal sukses ke Browser
        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Konten beranda baru telah ditambahkan.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }


    public function showEdit($id)
    {
        $p = Profile::findOrFail($id);

        $this->profileId = $id;
        $this->title = $p->title;
        $this->content = $p->content;
        $this->content_type = $p->content_type;
        $this->display_order = $p->display_order;
        $this->status = $p->status;
        $this->old_image = $p->image;

        $this->viewState = 'edit';
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'content_type' => 'required',
        ]);

        $p = Profile::findOrFail($this->profileId);
        $imageName = $this->old_image;

        if ($this->image) {
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                Storage::disk('public')->delete($this->old_image);
            }
            $imageName = $this->image->store('profiles', 'public');
        }

        $p->update([
            'title' => $this->title,
            'content' => $this->content,
            'content_type' => $this->content_type,
            'image' => $imageName,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'updated_by' => Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Diperbarui!',
            'text'  => 'Perubahan data berhasil disimpan.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function delete($id)
    {
        $p = Profile::findOrFail($id);

        if ($p->image && Storage::disk('public')->exists($p->image)) {
            Storage::disk('public')->delete($p->image);
        }

        $p->delete();

        // Menampilkan Alert Sukses Hapus
        $this->dispatch('swal:modal', [
            'title' => 'Dihapus!',
            'text'  => 'Data telah berhasil dihapus.',
            'icon'  => 'success'
        ]);

        session()->flash('success', 'Data berhasil dihapus!');
    }

    public function confirmUpdate()
    {
        // Memicu JavaScript untuk memunculkan SweetAlert tanya
        $this->dispatch('triggerUpdateConfirm');
    }
}



