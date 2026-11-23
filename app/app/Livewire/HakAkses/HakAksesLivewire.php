<?php

namespace App\Livewire\HakAkses;

use App\Helper\ConstHelper;
use App\Helper\ToolsHelper;
use App\Helper\WithComponentNameHelper;
use App\Http\Api\UserApi;
use App\Models\HakAksesModel;
use Livewire\Component;

class HakAksesLivewire extends Component
{
    use WithComponentNameHelper;

    // check akses
    private function checkAkses()
    {
        if (! $this->isEditor) {
            abort(403, 'Unauthorized action.');
        }
        $this->resetErrorBag();
    }

    // Mount
    public $auth;

    public $optionRoles = [];

    public function mount()
    {
        $this->auth = request()->auth;
        $this->optionRoles = ConstHelper::getOptionRoles();
    }

    // Render
    public $isEditor = false;

    public $search;

    public $searchPengguna;

    public function render()
    {
        // Ambil daftar akses

        $aksesList = HakAksesModel::get();
        $response = UserApi::postReqUsersByIds(
            ToolsHelper::getAuthToken(),
            $aksesList->pluck('user_id')->unique()->toArray(),
        );
        if ($response && isset($response->data->users)) {
            $usersList = collect($response->data->users)->map(function ($user) {
                return (object) $user;
            })->all();
        } else {
            $usersList = [];
        }

        foreach ($aksesList as $akses) {
            $akses->user = collect($usersList)->firstWhere('id', $akses->user_id);
        }

        if ($this->search) {
            $aksesList = $aksesList->filter(function ($item) {
                $user = $item->user;
                if (! $user) {
                    return false;
                }

                return str_contains(strtolower($user->name), strtolower($this->search)) ||
                    str_contains(strtolower($user->username), strtolower($this->search));
            });
        }

        // Peancarian data pengguna

        $searchPenggunaList = [];
        if ($this->searchPengguna) {
            $authToken = ToolsHelper::getAuthToken();
            $result = UserApi::getUsers($authToken, search: $this->searchPengguna, limit: 5, alias: '');
            if (isset($result->data->users)) {
                $searchPenggunaList = collect($result->data->users ?? [])->map(function ($user) {
                    return (object) $user;
                })->all();
            }
        }

        // dd($this->auth);

        // cek akses
        $this->isEditor = in_array('Delcom', $this->auth->akses) || in_array('Admin', $this->auth->roles);

        $data = [
            'aksesList' => $aksesList,
            'searchPenggunaList' => $searchPenggunaList,
        ];

        return view('features.hak-akses.hak-akses-livewire', $data);
    }

    // Properti untuk tambah data
    public $addUserId;

    public $addHakAkses;

    public function add()
    {
        $this->checkAkses();

        // Validasi input
        $this->validate([
            'addUserId' => 'required',
            'addHakAkses' => 'required|array',
        ], [
            'addUserId.required' => 'Pengguna harus dipilih.',
            'addHakAkses.required' => 'Hak akses harus dipilih.',
            'addHakAkses.array' => 'Hak akses harus berupa array.',
        ]);

        // Hapus akses lama
        HakAksesModel::where('user_id', $this->addUserId)->delete();

        // Simpan hak akses baru
        HakAksesModel::create([
            'id' => ToolsHelper::generateId(),
            'user_id' => $this->addUserId,
            'akses' => implode(',', $this->addHakAkses),
        ]);

        // Reset input
        $this->reset([
            'addUserId',
            'addHakAkses',
        ]);

        $this->dispatch('closeModal', id: 'addModal');

        $this->render();
    }

    // Properti untuk mengedit data
    public $editId;

    public $editHakAkses;

    public function prepareEdit($id)
    {
        $targetAkses = HakAksesModel::where('id', $id)->first();
        if (! $targetAkses) {
            return;
        }

        $this->editId = $targetAkses->id;
        $this->editHakAkses = explode(',', $targetAkses->akses);

        $this->dispatch('showModal', id: 'editModal');
    }

    public function edit()
    {
        $this->checkAkses();

        // Validasi input
        $this->validate([
            'editHakAkses' => 'required|array',
        ], [
            'editHakAkses.required' => 'Hak akses harus dipilih.',
            'editHakAkses.array' => 'Hak akses harus berupa array.',
        ]);

        // Update hak akses
        $akses = HakAksesModel::where('id', $this->editId)->first();
        $akses->akses = implode(',', $this->editHakAkses);
        $akses->save();

        // Reset input
        $this->reset([
            'editId',
            'editHakAkses',
        ]);

        $this->dispatch('closeModal', id: 'editModal');

        $this->render();
    }

    // Properie untuk menghapus data
    public $deleteId;

    public $deleteMessage;

    public function prepareDelete($id)
    {
        $targetAkses = HakAksesModel::find($id);
        if (! $targetAkses) {
            return;
        }

        $response = UserApi::getUserById(
            ToolsHelper::getAuthToken(),
            $targetAkses->user_id,
        );
        $name = isset($response->data->user) ? $response->data->user->name : '-';

        $this->deleteId = $targetAkses->id;
        $this->deleteMessage = "Apakah Anda yakin ingin menghapus hak akses untuk pengguna <strong>'$name'</strong>?";

        $this->dispatch('showModal', id: 'deleteModal');
    }

    public function delete()
    {
        $this->checkAkses();

        HakAksesModel::destroy($this->deleteId);

        $this->reset([
            'deleteId',
            'deleteMessage',
        ]);

        $this->dispatch('closeModal', id: 'deleteModal');

        $this->render();
    }
}



