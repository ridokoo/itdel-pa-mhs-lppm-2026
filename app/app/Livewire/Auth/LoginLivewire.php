<?php

namespace App\Livewire\Auth;

use App\Helper\ToolsHelper;
use App\Helper\WithComponentNameHelper;
use App\Http\Api\UserApi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class LoginLivewire extends Component
{
    use WithComponentNameHelper;

    public $globalErrorMessage = '';
    public $systemId;
    public $info;
    public $username;
    public $password;

    public function rendered()
    {
        ToolsHelper::setErrorMessage('');
    }

    public function mount()
    {
        $this->globalErrorMessage = ToolsHelper::getErrorMessage();

        $authToken = ToolsHelper::getAuthToken();
        if (!empty($authToken)) {
            // Jika token adalah sesi admin lokal, pastikan session Auth masih aktif
            if ($authToken === 'LOCAL_ADMIN_SESSION') {
                if (Auth::check()) {
                    return redirect()->route('index');
                }
            }
            $this->setup($authToken);
        }
    }

    public function render()
    {
        $urlLoginSSO = config('sdi.sso_authorize_url') . '?' . http_build_query([
            'client_id' => config('sdi.sso_client_id'),
        ]);

        $data = [
            'urlLoginSSO' => $urlLoginSSO,
        ];

        return view('features.auth.login-livewire', $data);
    }

    /**
     * Mengatur sesi setelah mendapatkan token
     */
    public function setup($authToken)
    {
        // 1. Khusus Sesi Admin Lokal
        if ($authToken === 'LOCAL_ADMIN_SESSION') {
            return Auth::check() ? redirect()->route('index') : ToolsHelper::setAuthToken('');
        }

        // 2. Cek Validitas Token ke API
        $resLoginInfo = UserApi::getLoginInfo($authToken);
        if ($resLoginInfo->status != 'success') {
            ToolsHelper::setAuthToken('');
            $this->dispatch('invalidAuthToken');
            return;
        }

        ToolsHelper::setAuthToken($authToken);

        // 3. Ambil data User dari API
        $response = UserApi::getMe($authToken);
        if ($response->status != 'success') {
            if (isset($response->message) && str_contains($response->message, 'diverifikasi')) {
                return redirect()->route('auth.totp');
            }
            return redirect()->route('auth.totp');
        }

        // 4. Sinkronisasi ke Database Lokal (Penting!)
        $this->syncLocalUser($response->data->user);

        return redirect()->route('index');
    }

    public function submit()
    {
        $this->resetErrorBag();
        $inputUsername = trim(strtolower($this->username));

        // ============================================================
        // 1. LOGIKA LOGIN ADMIN LPPM (LOKAL PgSQL)
        // ============================================================
        $localEmails = ['admin.lppm@del.ac.id', 'dosen.test@del.ac.id', 'reviewer.test@del.ac.id'];

if (in_array($inputUsername, $localEmails)) {
    $this->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt(['email' => $inputUsername, 'password' => $this->password])) {
        ToolsHelper::setAuthToken('LOCAL_ADMIN_SESSION');

        // Set role sesuai user
        $user = Auth::user();
        $role = $user->roles->first()->name ?? 'dosen';
        session(['active_role' => strtolower($role)]);

        return redirect()->route('index');
    }

    $this->addError('username', 'Password salah.');
    return;
}

        // ============================================================
        // 2. LOGIKA LOGIN MAHASISWA / DOSEN (API CIS)
        // ============================================================
        $this->validate([
            'systemId' => 'required|string|max:50',
            'info' => 'required|string|max:255',
            'username' => 'required|string|max:50',
            'password' => 'required|string',
        ]);

        try {
            $response = UserApi::postLogin(
                $this->systemId,
                config('app.name'),
                $this->info,
                $this->username,
                $this->password
            );

            if (!isset($response->data->token)) {
                $this->addError('username', $response->message ?? 'Gagal login, silakan coba lagi.');
                return;
            }

            $token = $response->data->token;

            // Jalankan setup untuk sinkronisasi data
            $this->setup($token);
        } catch (\Exception $e) {
            $this->addError('username', 'Terjadi kesalahan koneksi ke server CIS.');
        }
    }

    /**
     * Menyimpan data dari API ke tabel Users lokal
     */
 private function syncLocalUser($cisUser)
{
    // 1. Cari user berdasarkan email
    $user = User::where('email', $cisUser->email)->first();

    // Ambil alias dari CIS dan buat huruf kecil semua
    $aliasCIS = strtolower($cisUser->alias ?? '');

    // 2. Tentukan Role ID berdasarkan alias dari CIS
    $roleId = 3; // Default: Dosen
    if (str_contains($aliasCIS, 'mahasiswa') || str_contains($aliasCIS, 'student')) {
        $roleId = 2;
    } elseif (str_contains($aliasCIS, 'staff') || str_contains($aliasCIS, 'karyawan')) {
        $roleId = 4; // Asumsi 4 adalah Staff (sesuaikan dengan tabel roles Anda)
    } elseif (str_contains($aliasCIS, 'operator') || str_contains($aliasCIS, 'admin')) {
        $roleId = 1;
    }

    if (!$user) {
        // JIKA USER BARU: Simpan semua data termasuk role_id dan alias
        $user = User::create([
            'email'     => $cisUser->email,
            'name'      => $cisUser->name,
            'cis_id'    => $cisUser->username,
            'alias'     => $aliasCIS, // PENTING: Simpan alias agar terbaca di form usulan
            'role_id'   => $roleId,   // PENTING: Masukkan role_id yang sudah dihitung
            'password'  => Hash::make('dummy-password'),
            'is_active' => true
        ]);
    } else {
        // JIKA USER SUDAH ADA: Update data terbaru dari CIS
        $updateData = [
            'name'   => $cisUser->name,
            'cis_id' => $cisUser->username,
            'alias'  => $aliasCIS, // Update alias jika ada perubahan di CIS
        ];

        // KHUSUS: Jangan paksa update role_id jika dia adalah Admin LPPM Lokal
        // Agar role 'Admin' tidak berubah jadi 'Dosen' otomatis saat login
        if ($user->email !== 'admin.lppm@del.ac.id') {
            $updateData['role_id'] = $roleId;
        }

        $user->update($updateData);
    }

    // Login-kan user ke session Laravel
    Auth::login($user);
}
}



