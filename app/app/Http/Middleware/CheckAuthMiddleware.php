<?php

namespace App\Http\Middleware;

use App\Helper\ToolsHelper;
use App\Http\Api\UserApi;
use App\Models\HakAksesModel;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Role;
use App\Models\ResearcherIdentifier;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <--- PASTIKAN INI ADA
use Illuminate\Support\Facades\Http; // <--- PASTIKAN INI ADA
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property object $auth
 */
class CheckAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authToken = ToolsHelper::getAuthToken();

        // 1. LOGIKA ADMIN LPPM (LOKAL)
        if ($authToken === 'LOCAL_ADMIN_SESSION') {
            if (!Auth::check()) {
                ToolsHelper::setAuthToken('');
                return redirect()->route('auth.login');
            }

            $user = Auth::user();
            $request->auth = (object) $user->getAttributes();
            $userRoles = $user->roles->pluck('name')->toArray();
            $request->auth->role = strtolower($userRoles[0] ?? 'admin');
            $request->auth->local_id = $user->id;

                session(['active_role' => $request->auth->role]);


            return $next($request);
        }

        if (empty($authToken)) {
            return redirect()->route('auth.login');
        }

        // 2. LOGIKA MAHASISWA / DOSEN (API CIS)
        $response = UserApi::getMe($authToken);
        if (!isset($response->data->user)) {
            ToolsHelper::setAuthToken('');
            return redirect()->route('auth.login')->with('error', 'Sesi Anda telah berakhir.');
        }

        $request->auth = $response->data->user;

        // --- SINKRONISASI USER KE DATABASE LOKAL ---
        $emailApi = strtolower(trim((string) ($request->auth->email ?? '')));

        // Cari via cis_id dulu agar tidak duplikat jika email berubah
        $localUser = User::where('cis_id', (string) $request->auth->id)->first()
            ?? ($emailApi !== '' ? User::where('email', $emailApi)->first() : null);

        $userData = [
            'name'      => $request->auth->name,
            'cis_id'    => (string) $request->auth->id,
            'username'  => $request->auth->username,
            'alias'     => $request->auth->alias,
            'is_active' => true,
        ];

        // Email hanya dimasukkan jika tidak kosong
        if ($emailApi !== '') {
            $userData['email'] = $emailApi;
        }

        if (!$localUser) {
            // Guard: tidak boleh insert tanpa email
            if ($emailApi === '') {
                \Log::error('CheckAuthMiddleware: email kosong dari API', [
                    'name'   => $request->auth->name,
                    'cis_id' => $request->auth->id,
                ]);
                ToolsHelper::setAuthToken('');
                return redirect()->route('auth.login')
                    ->with('error', 'Akun Anda tidak memiliki email terdaftar. Hubungi administrator.');
            }
            $userData['password'] = Hash::make('dummy-password');
            $localUser = User::create($userData);
        } else {
            // Jangan overwrite email yang sudah ada dengan nilai kosong
            if ($emailApi === '') {
                unset($userData['email']);
            }
            $localUser->update($userData);
        }

        // ============================================================
        // PERBAIKAN 1: SINKRONISASI ROLE DARI API KE DATABASE LOKAL
        // Agar Sidebar tidak menampilkan "GUEST"
        // ============================================================
        $apiAlias = strtolower(trim((string) ($request->auth->alias ?? '')));

        if ($apiAlias !== '' && $apiAlias !== 'guest') {
            // firstOrCreate dengan LOWER+TRIM agar tidak pernah duplikat
            $roleInDb = Role::whereRaw('LOWER(TRIM(name)) = ?', [$apiAlias])->first();

            if (!$roleInDb) {
                // Cek sekali lagi dengan lock untuk hindari race condition
                $roleInDb = Role::firstOrCreate(
                    ['name' => $apiAlias],  // lowercase, sudah di-trim
                    ['name' => $apiAlias]
                );
            }

            $localUser->roles()->syncWithoutDetaching([$roleInDb->id]);
        }


        // ============================================================
        // PERBAIKAN 2: AKTIFKAN LOGIN LOKAL LARAVEL
        // Penting agar auth()->user() di Sidebar berfungsi
        // ============================================================
        if (!Auth::check() || Auth::id() !== $localUser->id) {
            Auth::login($localUser);
        }

        // 1. Ambil list role asli dari database lokal dan paksa jadi lowercase
        $userRoles = $localUser->roles->pluck('name')->map(fn($n) => strtolower($n))->toArray();

        // 2. CEK APAKAH PERIODE PROPOSAL SEDANG AKTIF (Tetap pertahankan logika Anda)
        $now = now()->format('Y-m-d');
        $isPeriodOpen = \App\Models\ProposalPeriod::where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->exists();

        // 3. LOGIKA PROTEKSI REVIEWER
        if (in_array('reviewer', $userRoles) && !$isPeriodOpen) {
            $userRoles = array_diff($userRoles, ['reviewer']);
        }

        // ============================================================
        // LOGIKA PRIORITAS: DOSEN ADALAH UTAMA
        // ============================================================

        // Ubah urutan: 'dosen' diletakkan paling depan
        $priority = ['dosen', 'staff', 'reviewer', 'operator', 'ketua'];

        // Ambil role pertama yang ditemukan berdasarkan urutan prioritas di atas
        $defaultRole = collect($priority)
            ->first(fn($p) => in_array($p, $userRoles)) ?? ($userRoles[0] ?? 'guest');

        // Set session 'active_role' hanya jika belum ada (saat login pertama kali)
        if (!session()->has('active_role')) {
            session(['active_role' => $defaultRole]);
        }

        // Ambil role yang sedang aktif dari session
        $activeRole = session('active_role');

        // Validasi tambahan: Jika active_role di session tidak ada dalam daftar roles user (misal hak akses dicabut)
        if (!in_array($activeRole, $userRoles)) {
            $activeRole = $defaultRole;
            session(['active_role' => $activeRole]);
        }

        // Gunakan $activeRole untuk menentukan tampilan Sidebar/Navbar
        $request->auth->role = $activeRole;
        $request->auth->all_roles = $userRoles;
        $request->auth->local_id = $localUser->id;

        // ============================================================
        // 4. LOGIKA PAKSA ISI PROFIL DOSEN (Hanya jalan jika mode aktif adalah 'dosen')
        // ============================================================
        if ($activeRole == 'dosen') {
            $profile = UserProfile::where('user_id', $localUser->id)->first();
            $hasIdentifier = \App\Models\ResearcherIdentifier::where('user_id', $localUser->id)->exists();

            if (!$profile || empty($profile->nidn) || !$hasIdentifier) {
                if (!$request->routeIs('dosen.profil') && !$request->routeIs('auth.logout')) {
                    return redirect()->route('dosen.profil');
                }
            }
        }

        // 4. DATA TAMBAHAN (HAK AKSES & FOTO)
        // PERBAIKAN 3: Gunakan ID dari API (UUID) untuk query hak akses
        $akses = HakAksesModel::where('user_id', $request->auth->id)->first();
        $request->auth->akses = isset($akses->akses) ? explode(',', $akses->akses) : [];

        $genderSuffix = (isset($request->auth->gender) && $request->auth->gender == 'F') ? 'F' : 'M';
        $request->auth->photo = $request->auth->photo ?? asset("assets/images/profile/{$genderSuffix}.jpg");

        return $next($request);
    }
}



