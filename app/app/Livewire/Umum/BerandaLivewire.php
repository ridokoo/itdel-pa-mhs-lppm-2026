<?php

namespace App\Livewire\Umum;

use App\Helper\ToolsHelper;
use App\Helper\WithComponentNameHelper;
use App\Http\Api\UserApi;
use Livewire\Component;

class BerandaLivewire extends Component
{
    use WithComponentNameHelper;

    // Mount
    public $authUser;

    public $waktuServer;

    public function mount()
    {
        $this->authUser = request()->auth;
        $this->waktuServer = now()->timezone('Asia/Jakarta')->toISOString();
    }

    public function render()
    {
        $resUsersOnline = UserApi::getUsersOnline(ToolsHelper::getAuthToken());
        $usersOnline = [];
        if (isset($resUsersOnline->data->users)) {
            $usersOnline = $resUsersOnline->data->users;
        }
        $totalUsersOnlie = count($usersOnline);
        // dd($usersOnline);

        $data = [
            'usersOnline' => $usersOnline,
            'totalUsersOnlie' => $totalUsersOnlie,
        ];

        return view('features.umum.beranda-livewire', $data);
    }
}



