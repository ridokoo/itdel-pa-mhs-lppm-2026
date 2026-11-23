<?php

namespace App\Http\Controllers;

use App\Helper\ApiHelper;
use App\Helper\ToolsHelper;
use App\Livewire\Auth\LoginLivewire;
use App\Livewire\Auth\LoginQrCodeLivewire;
use App\Livewire\Auth\RegisterLivewire;
use App\Livewire\Auth\TotpLivewire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        $data = [
            'title' => 'Register',
            'livewire' => RegisterLivewire::getComponentName(),
        ];

        return view('pages.auth', $data);
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
            'livewire' => LoginLivewire::getComponentName(),
        ];

        return view('auth.login', $data);
    }

    public function loginQRCode()
    {
        $data = [
            'title' => 'Login QRCode',
            'livewire' => LoginQrCodeLivewire::getComponentName(),
        ];

        return view('pages.auth', $data);
    }

    public function totp()
    {
        $data = [
            'title' => 'TOTP',
            'livewire' => TotpLivewire::getComponentName(),
        ];

        return view('auth.login', $data);
    }

    public function logout(Request $request)
    {
        // 1. Hapus Sesi Laravel (Lokal)
        Auth::logout();

        // 2. Hancurkan Sesi Browser & Token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 3. Reset Token API (PENTING! Ini yang bikin auto-login)
        // Menghapus cookie/session 'auth_token' yang ada di ToolsHelper
        ToolsHelper::setAuthToken('');

        // 4. Lempar kembali ke halaman login
        return redirect()->route('auth.login');
    }
    // SSO Callback
    public function ssoCallback(Request $request)
    {
        $code = $request->query('code');
        if (! $code) {
            return redirect()->route('auth.login')->with('error', 'Kode otorisasi tidak ditemukan');
        }

        $urlToken = config('sdi.sso_token_url');
        $clientId = config('sdi.sso_client_id');
        $clientSecret = config('sdi.sso_client_secret');

        $data = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
        ];

        $response = ApiHelper::sendRequest($urlToken, 'POST', $data);

        if (! isset($response->access_token)) {
            ToolsHelper::setErrorMessage(isset($response->message) ? $response->message : 'Gagal mendapatkan token akses dari SSO');

            return redirect()->route('auth.login');
        }

        // Simpan token akses di sesi atau cookie
        ToolsHelper::setAuthToken($response->access_token);

        return redirect()->route('index');
    }

    public function switchRole($role)
    {
        // Simpan peran terpilih ke session
        session(['active_role' => $role]);

        // Berikan notifikasi
        return redirect()->route('index')->with('success', 'Berhasil beralih ke peran ' . strtoupper($role));
    }

    //     public function switchRole($role)
    // {
    //     // Simpan role yang dipilih ke dalam session
    //     session(['active_role' => strtolower($role)]);

    //     // Redirect ke dashboard masing-masing atau kembali ke halaman sebelumnya
    //     if ($role == 'reviewer') {
    //         return redirect()->route('index'); // atau route dashboard reviewer
    //     }

    //     return redirect()->route('index');
    // }
}



