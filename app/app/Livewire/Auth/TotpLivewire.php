<?php

namespace App\Livewire\Auth;

use App\Helper\ToolsHelper;
use App\Helper\WithComponentNameHelper;
use App\Http\Api\UserApi;
use Livewire\Component;

class TotpLivewire extends Component
{
    use WithComponentNameHelper;

    public $authToken = '';

    public $qrCode;

    public function mount()
    {
        $this->authToken = ToolsHelper::getAuthToken();
        if (empty($this->authToken)) {
            return redirect()->route('auth.login');
        }

        $checkToken = UserApi::getLoginInfo($this->authToken);
        // dd($checkToken);
        if (isset($checkToken->data->login_info->is_verified) && $checkToken->data->login_info->is_verified) {
            return redirect()->route('index');
        }

        $response = UserApi::postTotpSetup($this->authToken);

        if (! $response || $response->status != 'success') {
            $this->qrCode = null;
        } else {
            $this->qrCode = $response->data->qrCode;
        }
    }

    public function render()
    {
        return view('features.auth.totp-livewire');
    }

    // Submit Data
    public $token;

    public function submit()
    {
        // Reset error bag sebelum validasi
        $this->resetErrorBag();

        // Validasi input
        $this->validate([
            'token' => 'required|numeric|digits:6',
        ]);

        if (empty($this->authToken)) {
            $this->addError('token', 'Token otentikasi tidak ditemukan.');

            return;
        }

        $response = UserApi::postTotpVerify($this->authToken, $this->token);
        if (! $response || $response->status != 'success') {
            $this->addError('token', $response->message ?? 'Kode TOTP tidak valid, silakan coba lagi.');

            return;
        }

        return redirect()->route('index');
    }
}



