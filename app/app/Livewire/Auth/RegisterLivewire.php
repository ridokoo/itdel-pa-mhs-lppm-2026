<?php

namespace App\Livewire\Auth;

use App\Helper\WithComponentNameHelper;
use App\Http\Api\UserApi;
use Livewire\Component;

class RegisterLivewire extends Component
{
    use WithComponentNameHelper;

    // Data Submit
    public $username;

    public $password;

    public function submit()
    {
        // Validasi input
        $this->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string',
        ]);

        $response = UserApi::postRegister(
            $this->username,
            $this->password
        );

        if (! $response || $response->status != 'success') {
            $this->addError('username', $response->message ?? 'Gagal melakukan pendaftaran, silakan coba lagi!');

            return;
        }

        return redirect()->route('auth.login')->with('success', 'Berhasil mendaftar, silakan masuk!');
    }

    public function render()
    {
        return view('features.auth.register-livewire');
    }
}



