<?php

namespace App\Livewire\Auth;

use App\Helper\ToolsHelper;
use App\Helper\WithComponentNameHelper;
use App\Http\Api\UserApi;
use Livewire\Component;

class LoginQrCodeLivewire extends Component
{
    use WithComponentNameHelper;

    public function mount()
    {
        $authToken = ToolsHelper::getAuthToken();
        if (! empty($authToken)) {
            $this->setup($authToken);
        }
    }

    public function render()
    {
        $data = [];

        return view('features.auth.login-qrcode-livewire', $data);
    }

    // Data Submit

    public $systemId;

    public $info;

    public $loginQRCode;

    public $loginId;

    public $isSetup = true;

    public function setup()
    {
        $resSetupLoginQRCode = UserApi::postLoginQRCodeSetup($this->systemId, config('app.name'), $this->info);
        if ($this->isSetup) {
            if (isset($resSetupLoginQRCode->data)) {
                $this->loginQRCode = $resSetupLoginQRCode->data->qrcode;
                $this->loginId = $resSetupLoginQRCode->data->value;
            } else {
                return redirect()->route('auth.login');
            }
        }

        $this->isSetup = false;
    }

    public function check()
    {
        if (! $this->loginId) {
            $this->dispatch('stopCheckLoginQRCode');

            return;
        }

        $resLoginQRCode = UserApi::getLoginQRCodeById($this->loginId);
        if (! isset($resLoginQRCode->data->login)) {
            $this->reset(['loginQRCode', 'loginId']);
            $this->dispatch('stopCheckLoginQRCode');

            return;
        }

        $dataLogin = $resLoginQRCode->data->login;

        if ($dataLogin->token) {
            $this->dispatch('stopCheckLoginQRCode');
            $this->dispatch('saveAuthToken', authToken: $dataLogin->token);
        }
    }
}



