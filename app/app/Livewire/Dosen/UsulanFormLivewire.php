<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{
    User,
    Role,
    Proposal,
    ProposalMember,
    ProposalStatus,
    Category,
    SchemeGroup,
    SchemeType,
    Scope,
    ProposalPeriod,
    BudgetItem,
    ExpenseCategory,
    ProposalOutputTarget,
    OutputTargetType,
    OutputTargetCategory,
    OutputTargetDetail,
    OutputTargetStatus,
    ProposalPartner,
    TargetTkt,
    CriteriaCategoryDetail
};

use App\Helper\{ToolsHelper, WithComponentNameHelper};
use Illuminate\Support\Facades\{Hash, Storage, DB};
use Livewire\Attributes\Url;
use App\Http\Api\UserApi;
use App\Notifications\SystemNotification;
use App\Services\NotificationService;

class UsulanFormLivewire extends Component
{
    use WithFileUploads, WithComponentNameHelper;

    // =========================================================================
    // PROPERTIES
    // =========================================================================

    #[Url]
    public $currentStep = 1;
    public $isReadOnly  = false;
    public $isEdit      = false;
    public $isLeader    = true;
    public $isAutoSave  = false;
    public bool $isAgreed = false;


    public $categoryId, $id_usulan;
    public $localUser;
    public $userProfile;
    public $totalRAB        = 0;
    public $totalPercentage = 0;
    public $isPeriodOpen    = false;
    public $activePeriodInfo = '';

    // --- STEP 1: IDENTITAS ---
    public $title, $summary, $target_tkt_id, $target_tkt_end, $total_budget, $duration_months = 1;
    public $scheme_group_id, $scheme_type_id, $scope_id, $period_id;

    // Dropdown data
    public $categories        = [];
    public $schemeGroups      = [];
    public $schemeTypes       = [];
    public $scopes            = [];
    public $targetTkts        = [];
    public $outputTargetTypes = [];
    public $expenseCategories = [];

    // Decision / eligibility
    public $decision_note       = '';
    public $isEligible          = true;
    public $eligibilityMessages = [];
    public $mandatoryOutputs    = [];
    public $mandatoryGuides     = [];

    // --- STEP 2: ANGGOTA ---
    public $addedDosen     = [];
    public $addedStaff     = [];
    public $addedMahasiswa = [];
    public $searchMember   = '';
    public $searchResult   = [];
    public $searchType     = '';

    // --- STEP 3: LUARAN ---
    public $outputTargets        = [];
    public $outputTargetCategories = [];
    public $outputTargetDetails    = [];
    public $outputTargetStatuses   = [];

    // --- STEP 4: RAB ---
    public $budgetItems    = [];
    public $categoryLimits = [];

    // --- STEP 5: MITRA ---
    public $addedMitra         = [];
    public $mitra_name, $mitra_email, $mitra_institution;
    public $mitra_identity, $mitra_address, $mitra_role, $mitra_qualification;

    // --- STEP 6: UNGGAH PROPOSAL ---
    public $proposal_file, $existing_proposal;

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    public function mount($categoryId, $id_usulan = null)
    {
        $this->categoryId = $categoryId;
        $this->id_usulan  = $id_usulan;
        $this->localUser  = User::where('email', request()->auth->email)->first();
        $this->userProfile = \App\Models\UserProfile::where('user_id', $this->localUser->id)->first();
        $this->outputTargetStatuses = OutputTargetStatus::orderBy('name')->get();
        $this->outputTargetDetails = OutputTargetDetail::where('is_active', true)->orderBy('name')->get();

        // --- Pengecekan Periode ---
        $now    = now()->format('Y-m-d');
        $period = ProposalPeriod::where('is_active', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->first();

        $this->isPeriodOpen    = (bool) $period;
        $this->activePeriodInfo = $period
            ? "Periode dibuka: {$period->name}"
            : "Pendaftaran usulan ditutup.";

        // --- Load dropdown data ---
        $this->categories          = Category::orderBy('name')->get();
        $this->schemeGroups        = SchemeGroup::where('category_id', $this->categoryId)->orderBy('name')->get();
        $this->scopes              = Scope::orderBy('name')->get();
        $this->targetTkts          = TargetTkt::orderBy('level')->get();
        $this->outputTargetTypes   = OutputTargetType::orderBy('name', 'desc')->get();
        $this->outputTargetStatuses = OutputTargetStatus::orderBy('name')->get();
        $this->expenseCategories   = ExpenseCategory::orderBy('name')->get();

        // SchemeTypes dikosongkan dulu; akan diisi saat scheme_group dipilih
        $this->schemeTypes = [];

        // --- Load data usulan jika edit ---
        if ($this->id_usulan) {
            $this->isEdit = true;

            $usulan = Proposal::with([
                'proposalMembers.user',
                'budgetItems',
                'outputTargets',
                'partners',
            ])->findOrFail($this->id_usulan);

            // Tentukan mode: read-only jika query param 'view' atau bukan ketua
            if (request()->query('mode') === 'view') {
                $this->isReadOnly = true;
                $this->currentStep = 7;
            }

            if ($usulan->leader_id !== $this->localUser->id) {
                $this->isLeader   = false;
                $this->isReadOnly = true;
            }

            // Muat scheme types sesuai kelompok skema yang tersimpan
            if ($usulan->scheme_group_id) {
                $this->schemeTypes = SchemeType::where('scheme_group_id', $usulan->scheme_group_id)->get();
            }

            $this->loadDataToProperties($usulan);
        }
    }

    // =========================================================================
    // LOAD DATA (EDIT MODE)
    // =========================================================================

    private function loadDataToProperties(Proposal $usulan): void
    {
        // Step 1
        $this->title           = $usulan->title;
        $this->summary         = $usulan->summary;
        $this->duration_months = $usulan->duration_months / 12;
        $this->target_tkt_id   = $usulan->target_tkt_id;
        $this->target_tkt_end  = $usulan->target_tkt_end;
        $this->total_budget    = number_format($usulan->total_budget, 0, ',', '.');
        $this->scheme_group_id = $usulan->scheme_group_id;
        $this->scheme_type_id  = $usulan->scheme_type_id;
        $this->scope_id        = $usulan->scope_id;
        $this->period_id       = $usulan->period_id;
        $this->existing_proposal = $usulan->proposal_file;
        $this->decision_note   = $usulan->decision_note;

        // Step 2 — Anggota
        foreach ($usulan->proposalMembers as $m) {
            $alias = strtolower($m->user->alias ?? '');
            $item  = [
                'user_id' => $m->user_id,
                'name'    => $m->user->name,
                'alias'   => $m->user->alias,
                'id_no'   => $m->user->nidn ?? $m->user->nim ?? '-',
            ];

            if (str_contains($alias, 'dosen'))       $this->addedDosen[]     = $item;
            elseif (str_contains($alias, 'staff'))   $this->addedStaff[]     = $item;
            else                                      $this->addedMahasiswa[] = $item;
        }

        // Step 3 — Luaran
        if ($usulan->outputTargets->count() > 0) {
            foreach ($usulan->outputTargets as $out) {
                $this->outputTargets[] = [
                    'id'                        => $out->id,
                    'output_target_type_id'     => $out->output_target_type_id,
                    'output_target_category_id' => $out->output_target_category_id,
                    'output_target_detail_id'   => $out->output_target_detail_id,
                    // 'output_target_status_id'   => $out->output_target_status_id,
                    'description'               => $out->description,
                ];
            }
        }

        // Step 4 — RAB
        foreach ($usulan->budgetItems as $item) {
            $this->budgetItems[] = [
                'id'                  => $item->id,
                'expense_category_id' => $item->expense_category_id,
                'component'           => $item->component,
                'item_name'           => $item->item_name,
                'volume'              => $item->volume,
                'unit'                => $item->unit,
                'unit_price'          => number_format($item->unit_price, 0, ',', '.'),
                'total'               => $item->total,
            ];
        }
        $this->calculateTotalRAB();

        // Step 5 — Mitra
        foreach ($usulan->partners as $m) {
            $this->addedMitra[] = [
                'identity_number' => $m->identity_number,
                'name'            => $m->name,
                'address'         => $m->address,
                'institution'     => $m->institution,
                'qualification'   => $m->qualification,
                'email'           => $m->email,
                'role_description' => $m->role_description,
            ];
        }
    }

    // =========================================================================
    // NAVIGASI STEP
    // =========================================================================

    public function nextStep(int $step): void
{
    if (!$this->isReadOnly && !empty($this->title)) {
        $this->isAutoSave = ($step !== 7); // Jangan silent fail saat ke step 7
        try {
            $this->saveUsulan();
        } catch (\Exception $e) {
            if ($step === 7) {
                $this->dispatch('showErrorAlert', 'Gagal menyimpan: ' . $e->getMessage());
                return;
            }
        } finally {
            $this->isAutoSave = false;
        }
    }

    match ($step) {
        5 => $this->goToStep5(),
        6 => $this->goToStep6(),
        7 => $this->goToStep7(),
        default => $this->currentStep = $step,
    };
}

    public function goToStep5(): void
    {
        $this->calculateTotalRAB();

        $paguDosen = (float) str_replace('.', '', $this->total_budget ?: 0);

        if ($this->totalRAB > $paguDosen) {
            $this->dispatch('showErrorAlert', 'Total rincian melebihi anggaran yang Anda tentukan di Step 1!');
            return;
        }

        if ($this->totalRAB < $paguDosen) {
            $sisa = number_format($paguDosen - $this->totalRAB, 0, ',', '.');
            $this->dispatch('showErrorAlert', "Total rincian belum mencapai 100% dari anggaran. Sisa: Rp {$sisa}");
            return;
        }

        foreach ($this->categoryLimits as $catId => $limit) {
            if (!$limit['is_valid']) {
                $catName = ExpenseCategory::find($catId)?->name ?? "ID: {$catId}";
                $this->dispatch('showErrorAlert', "Batas persentase kategori [{$catName}] tidak terpenuhi.");
                return;
            }
        }

        $this->currentStep = 5;
    }

    public function goToStep6(): void
    {
        if ($this->categoryId == 3 && count($this->addedMitra) === 0) {
            $this->dispatch('showErrorAlert', 'Wajib ada Mitra untuk kategori ini!');
            return;
        }
        $this->currentStep = 6;
    }

    public function goToStep7(): void
{
    // Hapus autoSaveDraft() dari sini — sudah dipanggil di nextStep()
    if (!$this->id_usulan) {
        $this->dispatch('showErrorAlert', 'Gagal menyimpan draft. Pastikan judul sudah diisi!');
        $this->currentStep = 1;
        return;
    }

    if (empty($this->title) || empty($this->scheme_type_id) || empty($this->scope_id)) {
        $this->dispatch('showErrorAlert', 'Data identitas belum lengkap (judul/skema/ruang lingkup)!');
        $this->currentStep = 1;
        return;
    }

    $this->currentStep = 7;
}

    public function updatedCurrentStep(int $value): void
    {
        // Auto-save draft saat user mengklik tab step langsung (step 1–6)
        if ($value >= 1 && $value <= 6 && !empty($this->title)) {
            $this->autoSaveDraft();
        }
    }

    // =========================================================================
    // SAVE / AUTO-SAVE
    // =========================================================================

    public function autoSaveDraft(): void
    {
        if (empty($this->title)) return;

        $this->isAutoSave = true;
        try {
            $this->saveUsulan();
        } catch (\Exception $e) {
            // Silent fail untuk auto-save
        } finally {
            $this->isAutoSave = false;
        }
    }

    public function saveUsulan(): void
{
    $this->validate(['title' => 'required|min:3']);

    if (!$this->isEdit && (empty($this->scheme_type_id) || empty($this->scope_id))) {
        $this->dispatch('showWarningAlert', 'Untuk hasil terbaik, lengkapi data skema dan ruang lingkup sebelum menyimpan draft.');
    }

    DB::beginTransaction();
    try {
        // 1. Handle file proposal (Step 6)
        $finalPath = $this->existing_proposal;
        if ($this->proposal_file) {
            $filename  = time() . '_' . $this->proposal_file->getClientOriginalName();
            $finalPath = $this->proposal_file->storeAs('usulan/final', $filename, 'public');

            if ($this->isEdit && $this->existing_proposal) {
                Storage::disk('public')->delete($this->existing_proposal);
            }
        }

        // 2. Handle file substansi (Step 3)

        // 3. Simpan data induk (tabel proposals)
        $status = ProposalStatus::whereRaw("LOWER(name) = 'draft'")->first();

        if (!$status) {
            DB::rollBack();
            $this->dispatch('showErrorAlert', 'Status Draft tidak ditemukan di database. Hubungi administrator.');
            return;
        }

        $payload = [
            'title'           => $this->title,
            'summary'         => $this->summary,
            'category_id'     => $this->categoryId,
            'scheme_group_id' => $this->scheme_group_id,
            'scheme_type_id'  => $this->scheme_type_id,
            'scope_id'        => $this->scope_id,
            'period_id'       => $this->period_id,
            'target_tkt_id'   => $this->target_tkt_id,
            'target_tkt_end'  => $this->target_tkt_end,
            'duration_months' => (int) $this->duration_months * 12,
            'total_budget'    => (float) str_replace('.', '', $this->total_budget ?: 0),
            'proposal_file'   => $finalPath,
            'updated_by'      => $this->localUser->id,
        ];

        $usulan = ($this->isEdit && $this->id_usulan)
            ? Proposal::findOrFail($this->id_usulan)
            : new Proposal();

        if (!$this->isEdit) {
            $payload['leader_id']  = $this->localUser->id;
            $payload['status_id']  = $status->id;
            $payload['created_by'] = $this->localUser->id;
        }

        $usulan->fill($payload)->save();
        $this->id_usulan = $usulan->id;

        // 4. Sinkronisasi data anak
        $this->syncChildren($usulan);

        DB::commit();

        $this->isEdit            = true;
        $this->existing_proposal = $finalPath;

        if (!$this->isAutoSave) {
            $this->dispatch('showSuccessAlert', 'Seluruh draf usulan berhasil disimpan!');
        }
    } catch (\Exception $e) {
        DB::rollBack();
        if (!$this->isAutoSave) {
            dd($e->getMessage());
        } else {
            \Log::error('AutoSave gagal: ' . $e->getMessage());
        }
    }
}

    private function syncChildren(Proposal $usulan): void
    {
        // --- RAB ---
        BudgetItem::where('proposal_id', $usulan->id)->delete();
        foreach ($this->budgetItems as $item) {
            if (!empty($item['item_name'])) {
                BudgetItem::create([
                    'proposal_id'         => $usulan->id,
                    'expense_category_id' => $item['expense_category_id'],
                    'component'           => $item['component'],
                    'item_name'           => $item['item_name'],
                    'volume'              => (float) $item['volume'],
                    'unit'                => $item['unit'],
                    'unit_price'          => (float) str_replace('.', '', $item['unit_price']),
                    'total'               => (float) $item['total'],
                    'created_by'          => $this->localUser->id,
                ]);
            }
        }

        // --- Luaran ---
        ProposalOutputTarget::where('proposal_id', $usulan->id)->delete();
        foreach ($this->outputTargets as $target) {
            if (!empty($target['output_target_type_id'])) {
                ProposalOutputTarget::create([
                    'proposal_id'               => $usulan->id,
                    'output_target_type_id'     => $target['output_target_type_id'],
                    'output_target_category_id' => $target['output_target_category_id'] ?: null,
                    'output_target_detail_id'   => $target['output_target_detail_id'] ?: null,
                    'output_target_status_id'   => null, // Di-null-kan dulu karena diisi di Laporan Kemajuan
                    'description'               => $target['description'] ?? null,
                    'created_by'                => $this->localUser->id,
                ]);
            }
        }

        // --- Mitra ---
        ProposalPartner::where('proposal_id', $usulan->id)->delete();
        foreach ($this->addedMitra as $m) {
            ProposalPartner::create([
                'proposal_id'      => $usulan->id,
                'identity_number'  => $m['identity_number'],
                'name'             => $m['name'],
                'institution'      => $m['institution'],
                'email'            => $m['email'],
                'address'          => $m['address'],
                'role_description' => $m['role_description'],
                'created_by'       => $this->localUser->id,
            ]);
        }

        // --- ANGGOTA (Logika Baru yang Konsisten) ---
        $allMembers = array_merge($this->addedDosen, $this->addedStaff, $this->addedMahasiswa);
        $newUserIds = collect($allMembers)->pluck('user_id')->toArray();

        // 1. Hapus anggota yang dikeluarkan dari UI
        ProposalMember::where('proposal_id', $usulan->id)
            ->whereNotIn('user_id', $newUserIds)
            ->delete();

        // 2. Kelola anggota yang ada/baru
        foreach ($allMembers as $m) {
            $isMahasiswa = collect($this->addedMahasiswa)->contains('user_id', $m['user_id']);

            // Cari apakah sudah ada di database
            $memberRecord = ProposalMember::where('proposal_id', $usulan->id)
                ->where('user_id', $m['user_id'])
                ->first();

            if ($memberRecord) {
                // JIKA SUDAH ADA: Hanya update status jika dia mahasiswa
                if ($isMahasiswa) {
                    $memberRecord->update(['member_status_id' => 2]);
                }
            } else {
                // JIKA BELUM ADA (Anggota Baru):
                $statusId = $isMahasiswa ? 2 : 1;


                ProposalMember::create([
                    'proposal_id'      => $usulan->id,
                    'user_id'          => $m['user_id'],
                    'member_status_id' => $statusId,
                ]);

                // Kirim notifikasi
                $userTujuan = User::find($m['user_id']);
                if ($userTujuan) {
                    $notifTitle = $isMahasiswa ? 'Anda Ditambahkan ke Tim Usulan' : 'Undangan Bergabung Tim Usulan';
                    $notifMsg   = $isMahasiswa
                        ? "Anda telah ditambahkan ke dalam tim usulan '{$usulan->title}' oleh {$this->localUser->name}."
                        : "Anda diundang bergabung dalam usulan '{$usulan->title}' oleh {$this->localUser->name}.";

                    app(NotificationService::class)->send(
                        userId: $userTujuan->id,
                        title: $notifTitle,
                        message: $notifMsg,
                        options: [
                            'url'         => $isMahasiswa ? '#' : route('dosen.undangan'),
                            'icon'        => $isMahasiswa ? 'ph-user-check' : 'ph-user-plus',
                            'proposal_id' => $usulan->id,
                            'event_type'  => $isMahasiswa ? 'proposal_member_added' : 'proposal_member_invitation',
                            'roles'       => [$userTujuan->roles->first()?->name ? strtolower($userTujuan->roles->first()->name) : 'mahasiswa'],
                        ]
                    );
                }
            }
        }
    }

    // =========================================================================
    // SUBMIT FINAL
    // =========================================================================

    public function finalSubmit()
    {
        if (!$this->id_usulan) return;

        $proposal  = Proposal::findOrFail($this->id_usulan);
        $statusAju = ProposalStatus::where('name', 'like', '%aju%')->first();

        $proposal->update([

            'status_id'    => $statusAju->id ?? 2,
            'submitted_at' => now(),
        ]);


        // Notifikasi ke reviewer & ketua LPPM
        $reviewers = User::whereHas('roles', fn($q) => $q->whereLike('name', '%reviewer%'))->get();
        $ketuas    = User::whereHas('roles', fn($q) => $q->whereLike('name', '%ketua%'))->get();
        $targets   = $reviewers->merge($ketuas)->unique('id');

        foreach ($targets as $user) {
            $isReviewer = $user->roles->contains(fn($r) => str_contains(strtolower($r->name), 'reviewer'));
            $url        = $isReviewer ? route('reviewer.dashboard') : route('ketua.dashboard');

            app(\App\Services\NotificationService::class)->send(
                userId: $user->id,
                title: 'Usulan Baru Diajukan',
                message: "Usulan '{$proposal->title}' telah diajukan dan membutuhkan peninjauan.",
                options: [
                    'url'         => $url,
                    'icon'        => 'ph-bell-ring',
                    'proposal_id' => $proposal->id,
                    'event_type'  => 'proposal_submitted',
                    'roles'       => $isReviewer ? ['reviewer'] : ['ketua'],
                ]
            );
        }

        return redirect()->route('dosen.usulan.index', ['categoryId' => $this->categoryId]);
    }

    // =========================================================================
    // ANGGOTA
    // =========================================================================

    public function openSearch(string $type): void
    {
        $this->searchType = $type;

        if (in_array($type, ['dosen', 'staff'])) {
            $currentQuota = count($this->addedDosen) + count($this->addedStaff);
            if ($currentQuota >= $this->getMaxMemberLimit()) {
                $this->dispatch('showErrorAlert', 'Kuota maksimal tim (Dosen & Staff) sudah terpenuhi.');
                return;
            }
        }

        $this->reset(['searchMember', 'searchResult']);
        $this->dispatch('showModal', id: '#modal_search_member');
    }

    public function updatedSearchMember(): void
    {
        if (strlen($this->searchMember) < 3) return;

        $token    = ToolsHelper::getAuthToken();
        $apiAlias = match ($this->searchType) {
            'dosen' => 'Dosen',
            'staff' => 'Staff',
            default => 'Mahasiswa',
        };

        $response = UserApi::getUsers($token, $this->searchMember, 10, $apiAlias);

        if (isset($response->data->users)) {
            $this->searchResult = collect($response->data->users)->map(fn($u) => [
                'id'       => $u->id,
                'name'     => $u->name,
                'email'    => $u->email,
                'alias'    => $u->alias ?? 'User',
                'username' => $u->username ?? '',
                'nidn'     => $u->nidn ?? null,
                'nim'      => $u->nim ?? null,
                'source'   => 'api',
            ])->take(5)->toArray();
        }
    }

    public function addMember(array $data): void
    {
        // Pastikan email tidak pernah null — fallback ke username@itdel.ac.id
        $emailRaw = trim($data['email'] ?? '');
        $email    = $emailRaw !== ''
            ? $emailRaw
            : (trim($data['username'] ?? '') !== ''
                ? $data['username'] . '@itdel.ac.id'
                : $data['id'] . '@itdel.ac.id');

        // Tambahan guard: log jika fallback dipakai
        if ($emailRaw === '') {
            \Log::warning('addMember: email kosong dari API, fallback ke ' . $email, $data);
        }

        $roleId = match ($this->searchType) {
            'dosen' => 3,
            'staff' => 4,
            default => 2,
        };

        // Cari dulu berdasarkan cis_id agar tidak bentrok jika email berubah
        $user = User::where('cis_id', $data['id'])->first();

        if ($user) {
            // Update data terbaru dari API
            $user->update([
                'name'      => $data['name'],
                'username'  => $data['username'],
                'alias'     => $data['alias'],
                'nidn'      => $data['nidn'] ?? null,
                'nim'       => $data['nim'] ?? null,
                'role_id'   => $roleId,
                'is_active' => true,
            ]);
        } else {
            // Cek lagi berdasarkan email sebelum insert baru
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update([
                    'name'      => $data['name'],
                    'cis_id'    => $data['id'],
                    'username'  => $data['username'],
                    'alias'     => $data['alias'],
                    'nidn'      => $data['nidn'] ?? null,
                    'nim'       => $data['nim'] ?? null,
                    'role_id'   => $roleId,
                    'is_active' => true,
                ]);
            } else {
                // Benar-benar user baru — insert dengan email yang sudah dijamin tidak null
                $user = User::create([
                    'name'      => $data['name'],
                    'email'     => $email,   // dijamin tidak null
                    'cis_id'    => $data['id'],
                    'username'  => $data['username'],
                    'alias'     => $data['alias'],
                    'nidn'      => $data['nidn'] ?? null,
                    'nim'       => $data['nim'] ?? null,
                    'role_id'   => $roleId,
                    'password'  => Hash::make('dummy'),
                    'is_active' => true,
                ]);
            }
        }

        $idNo = ($this->searchType === 'dosen')
            ? ($user->nidn ?? '-')
            : ($user->nim ?? $user->username ?? '-');

        $item = ['user_id' => $user->id, 'name' => $user->name, 'alias' => $user->alias, 'id_no' => $idNo];

        if ($this->searchType === 'dosen')      $this->addedDosen[]     = $item;
        elseif ($this->searchType === 'staff')  $this->addedStaff[]     = $item;
        else                                    $this->addedMahasiswa[] = $item;

        $this->dispatch('hideModal', id: '#modal_search_member');
    }

    public function removeDosen(int $i): void
    {
        unset($this->addedDosen[$i]);
        $this->addedDosen = array_values($this->addedDosen);
    }

    public function removeStaff(int $i): void
    {
        unset($this->addedStaff[$i]);
        $this->addedStaff = array_values($this->addedStaff);
    }

    public function removeMahasiswa(int $i): void
    {
        unset($this->addedMahasiswa[$i]);
        $this->addedMahasiswa = array_values($this->addedMahasiswa);
    }

    // =========================================================================
    // LUARAN
    // =========================================================================

    public function addOutputRow(int $typeId): void
    {
        $this->outputTargets[] = [
            'output_target_type_id'     => $typeId,
            'output_target_category_id' => '',
            'output_target_detail_id'   => '',
            // 'output_target_status_id'   => '',
            'description'               => '',
        ];
    }

    public function removeOutputRow(int $i): void
    {
        unset($this->outputTargets[$i]);
        $this->outputTargets = array_values($this->outputTargets);
    }

    // =========================================================================
    // RAB
    // =========================================================================

    public function addBudgetRow(int $catId): void
    {
        $this->budgetItems[] = [
            'expense_category_id' => $catId,
            'component'           => '',
            'item_name'           => '',
            'volume'              => 0,
            'unit'                => '',
            'unit_price'          => 0,
            'total'               => 0,
        ];
    }

    public function removeBudgetRow(int $index): void
    {
        if (isset($this->budgetItems[$index])) {
            unset($this->budgetItems[$index]);
            $this->budgetItems = array_values($this->budgetItems);
            $this->calculateTotalRAB();
        }
    }

    public function calculateTotalRAB(): void
    {
        $this->totalRAB = collect($this->budgetItems)->sum('total');

        $maxPaguSkema = $this->getSchemeMaxBudget();
        $paguPanduan  = $maxPaguSkema > 0 ? $maxPaguSkema : 1_000_000_000; // fallback besar

        // Sinkronkan total_budget di Step 1
        if ($this->totalRAB > 0) {
            $this->total_budget = number_format($this->totalRAB, 0, ',', '.');
        }

        // Hitung limit per kategori
        foreach ($this->expenseCategories as $cat) {
            $spent  = collect($this->budgetItems)->where('expense_category_id', $cat->id)->sum('total');
            $min_rp = ($cat->percentage_min / 100) * $paguPanduan;
            $max_rp = ($cat->percentage_max / 100) * $paguPanduan;

            $this->categoryLimits[$cat->id] = [
                'spent'    => $spent,
                'min_rp'   => $min_rp,
                'max_rp'   => $max_rp,
                'is_valid' => ($spent <= $max_rp),
            ];
        }
    }

    // =========================================================================
    // MITRA
    // =========================================================================

    public function addMitraToList(): void
    {
        $this->validate([
            'mitra_name'        => 'required',
            'mitra_institution' => 'required',
            'mitra_email'       => 'required|email',
            'mitra_identity'    => 'required',
        ]);

        $this->addedMitra[] = [
            'name'             => $this->mitra_name,
            'email'            => $this->mitra_email,
            'institution'      => $this->mitra_institution,
            'identity_number'  => $this->mitra_identity,
            'qualification'    => $this->mitra_qualification,
            'address'          => $this->mitra_address,
            'role_description' => $this->mitra_role,
        ];

        $this->reset([
            'mitra_name',
            'mitra_email',
            'mitra_institution',
            'mitra_identity',
            'mitra_address',
            'mitra_role',
            'mitra_qualification',
        ]);

        $this->dispatch('hideModal', id: '#modal_tambah_mitra');
    }
    public function exportRAB()
    {
        $usulan = $this->id_usulan
            ? Proposal::with(['schemeType', 'period', 'scope', 'category'])->find($this->id_usulan)
            : null;

        session([
            'rab_export' => [
                'items'      => $this->budgetItems,
                'categories' => $this->expenseCategories->map(fn($cat) => [
                    'id'               => $cat->id,
                    'name'             => $cat->name,
                    'budget_limit_min' => $cat->budget_limit_min ?? null,
                    'budget_limit_max' => $cat->budget_limit_max ?? null,
                ])->toArray(),
                'total'    => $this->totalRAB ?? 0,
                'research' => [
                    'title'         => $usulan?->title             ?? $this->title ?? '-',
                    'leader'        => $this->localUser->name      ?? '-',
                    'scheme'        => $usulan?->schemeType?->name ?? '-',
                    'period'        => $usulan?->period?->name     ?? '-',
                    'faculty'       => $usulan?->scope?->name      ?? '-',
                    'study_program' => $usulan?->category?->name   ?? '-',
                ],
            ]
        ]);

        return redirect()->route('export.rab');
    }

    public function removeMitra(int $i): void
    {
        unset($this->addedMitra[$i]);
        $this->addedMitra = array_values($this->addedMitra);
    }

    // =========================================================================
    // WATCHERS
    // =========================================================================

    public function updated(string $propertyName): void
    {
        // Kalkulasi otomatis total baris RAB
        if (str_contains($propertyName, 'budgetItems')) {
            $parts = explode('.', $propertyName);
            if (count($parts) === 3) {
                $i     = (int) $parts[1];
                $vol   = (float) ($this->budgetItems[$i]['volume'] ?? 0);
                $price = (float) str_replace('.', '', $this->budgetItems[$i]['unit_price'] ?? '0');
                $this->budgetItems[$i]['total'] = $vol * $price;
                $this->calculateTotalRAB();
            }
        }

        // Format angka total_budget
        if ($propertyName === 'total_budget') {
            $val = preg_replace('/[^0-9]/', '', $this->total_budget);
            $this->total_budget = $val ? number_format((float) $val, 0, ',', '.') : '';
        }

        $this->calculateTotalRAB();
    }

    public function updatedSchemeGroupId(int|string $value): void
    {
        $this->schemeTypes    = SchemeType::where('scheme_group_id', $value)->get();
        $this->scheme_type_id = null;
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    public function getMaxMemberLimit(): int
    {
        if (!$this->scheme_type_id) return 10;

        $rule = CriteriaCategoryDetail::where('scheme_type_id', $this->scheme_type_id)->first();
        return $rule ? (int) $rule->max_members : 10;
    }

    public function getMinMemberLimit(): int
    {
        if (!$this->scheme_type_id) return 0;

        $rule = CriteriaCategoryDetail::where('scheme_type_id', $this->scheme_type_id)->first();
        // Jika di database ada kolom min_members gunakan itu, jika tidak ada, kita asumsikan minimal 1
        return $rule ? (int) ($rule->min_members ?? 1) : 1;
    }

    public function isMemberRequirementMet(): bool
    {
        $currentTotal = count($this->addedDosen) + count($this->addedStaff);
        $minRequired = $this->getMinMemberLimit();

        return $currentTotal >= $minRequired;
    }

    public function getSchemeMaxBudget(): float
    {
        if (!$this->scheme_type_id) return 0;

        $rule = CriteriaCategoryDetail::where('scheme_type_id', $this->scheme_type_id)->first();
        return $rule ? (float) $rule->max_budget : 0;
    }



    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        // DEBUG SEMENTARA

        return view('features.dosen.usulan.form-livewire', [
            'schemeGroups'         => SchemeGroup::where('category_id', $this->categoryId)
                ->orderBy('name')
                ->get(),
            'schemeTypes'          => $this->scheme_group_id
                ? SchemeType::where('scheme_group_id', $this->scheme_group_id)
                ->orderBy('name')
                ->get()
                : collect(),
            'scopes'               => Scope::orderBy('name')->get(),
            'categories'           => $this->categories,
            'targetTkts'           => $this->targetTkts,
            'outputTargetTypes'    => $this->outputTargetTypes,
            'outputTargetStatuses' => $this->outputTargetStatuses,
            'expenseCategories'    => $this->expenseCategories,
            'mandatoryGuides'      => $this->mandatoryGuides,
            'categoriesByType'     => \App\Models\OutputTargetCategory::all()->groupBy('output_target_type_id'),
            'detailsByCategory' => \App\Models\OutputTargetDetail::all()->groupBy('output_target_category_id'),
        ]);
    }
}



