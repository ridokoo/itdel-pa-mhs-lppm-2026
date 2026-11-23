<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Announcement;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanManager extends Component
{
    use WithFileUploads;

    public $announcements, $annId;
    public $title, $content, $start_date, $end_date, $status = 'published';
    public $image, $old_image, $attachment, $old_attachment;
    public $viewState = 'index';

    public function render()
    {
        $this->announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('features.admin.pengumuman-manager-livewire')->layout('app.admin.index');
    }

    public function showIndex() { $this->resetFields(); $this->viewState = 'index'; }
    public function showCreate() { $this->resetFields(); $this->viewState = 'create'; }

    public function resetFields()
    {
        $this->title = ''; $this->content = ''; $this->start_date = ''; $this->end_date = '';
        $this->image = null; $this->old_image = null; $this->attachment = null;
        $this->old_attachment = null; $this->annId = null; $this->status = 'published';
        $this->resetErrorBag();
    }

public function store()
{
    $this->validate([
        'title'      => 'required|max:255',
        'content'    => 'required',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'image'      => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
    ]);

    $imgPath = $this->image ? $this->image->store('announcements/images', 'public') : null;
    $atPath  = $this->attachment ? $this->attachment->store('announcements/files', 'public') : null;

    Announcement::create([
        'title'           => $this->title,
        'content'         => $this->content,
        'start_date'      => $this->start_date,
        'end_date'        => $this->end_date,
        'image'           => $imgPath,
        'attachment_file' => $atPath,
        'status'          => $this->status,
        'created_by'      => Auth::id(),
    ]);

    $this->dispatch('swal:modal', ['title' => 'Berhasil!', 'text' => 'Pengumuman baru telah dibuat.', 'icon' => 'success']);
    $this->showIndex();
}


    public function showEdit($id)
    {
        $a = Announcement::findOrFail($id);
        $this->annId = $id;
        $this->title = $a->title;
        $this->content = $a->content;
        $this->start_date = $a->start_date;
        $this->end_date = $a->end_date;
        $this->status = $a->status;
        $this->old_image = $a->image;
        $this->old_attachment = $a->attachment_file;
        $this->viewState = 'edit';
    }

public function confirmUpdate()
{
    // HAPUS dispatch SweetAlert, langsung validasi & simpan
    $this->validate([
        'title'      => 'required|max:255',
        'content'    => 'required',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'image'      => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
    ]);

    $a       = Announcement::findOrFail($this->annId);
    $imgPath = $this->old_image;
    $atPath  = $this->old_attachment;

    if ($this->image) {
        if ($this->old_image) Storage::disk('public')->delete($this->old_image);
        $imgPath = $this->image->store('announcements/images', 'public');
    }

    if ($this->attachment) {
        if ($this->old_attachment) Storage::disk('public')->delete($this->old_attachment);
        $atPath = $this->attachment->store('announcements/files', 'public');
    }

    $a->update([
        'title'           => $this->title,
        'content'         => $this->content,
        'start_date'      => $this->start_date,
        'end_date'        => $this->end_date,
        'image'           => $imgPath,
        'attachment_file' => $atPath,
        'status'          => $this->status,
        'updated_by'      => Auth::id(),
    ]);

    $this->dispatch('swal:modal', [
        'title' => 'Diperbarui!',
        'text'  => 'Data pengumuman telah berhasil diperbarui.',
        'icon'  => 'success'
    ]);

    $this->showIndex();
}

// Method update() tetap ada tapi tidak dipakai dari form langsung
// (dipakai dari SweetAlert lama yang sudah tidak diperlukan lagi)
public function update()
{
    $this->confirmUpdate();
}
}



