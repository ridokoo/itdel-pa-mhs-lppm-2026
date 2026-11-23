<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contact;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KontakManager extends Component
{
    use WithFileUploads;

    public $contacts, $contactId;
    public $name, $contact_value, $icon, $display_order = 1;
    public $image, $old_image;
    public $viewState = 'index';

    public function render()
    {
        $this->contacts = Contact::orderBy('display_order', 'asc')->get();
        return view('features.admin.kontak-manager-livewire')->layout('app.admin.index');
    }

    public function showIndex() { $this->resetFields(); $this->viewState = 'index'; }
    public function showCreate() { $this->resetFields(); $this->viewState = 'create'; }

    public function resetFields() {
        $this->reset(['name', 'contact_value', 'icon', 'display_order', 'image', 'old_image', 'contactId']);
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255',
            'contact_value' => 'required',
        ]);

        $path = $this->image ? $this->image->store('contacts', 'public') : null;

        Contact::create([
            'name' => $this->name,
            'contact_value' => $this->contact_value,
            'icon' => $this->icon,
            'image' => $path,
            'display_order' => $this->display_order,
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Berhasil!',
            'text'  => 'Kontak baru telah ditambahkan.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function showEdit($id)
    {
        $c = Contact::findOrFail($id);
        $this->contactId = $id;
        $this->name = $c->name;
        $this->contact_value = $c->contact_value;
        $this->icon = $c->icon;
        $this->display_order = $c->display_order;
        $this->old_image = $c->image;
        $this->viewState = 'edit';
    }

    public function confirmUpdate()
    {
        $this->dispatch('triggerUpdateConfirm');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255',
            'contact_value' => 'required',
        ]);

        $c = Contact::findOrFail($this->contactId);
        $path = $this->old_image;

        if ($this->image) {
            if ($this->old_image) { Storage::disk('public')->delete($this->old_image); }
            $path = $this->image->store('contacts', 'public');
        }

        $c->update([
            'name' => $this->name,
            'contact_value' => $this->contact_value,
            'icon' => $this->icon,
            'image' => $path,
            'display_order' => $this->display_order,
            'updated_by' => Auth::id(),
        ]);

        $this->dispatch('swal:modal', [
            'title' => 'Diperbarui!',
            'text'  => 'Data kontak berhasil diperbarui.',
            'icon'  => 'success'
        ]);

        $this->showIndex();
    }

    public function delete($id)
    {
        $c = Contact::findOrFail($id);
        if ($c->image) { Storage::disk('public')->delete($c->image); }
        $c->delete();

        $this->dispatch('swal:modal', [
            'title' => 'Dihapus!',
            'text'  => 'Data kontak telah dihapus.',
            'icon'  => 'success'
        ]);
    }
}



