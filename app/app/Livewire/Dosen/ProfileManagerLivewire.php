<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\StudyProgram;
use App\Models\FunctionalPosition;
use App\Models\DatabaseIndex;
use App\Models\ResearcherIdentifier;
use App\Helper\WithComponentNameHelper;
use Illuminate\Support\Facades\DB;

class ProfileManagerLivewire extends Component
{
    use WithComponentNameHelper;

    public $nidn, $study_program_id, $functional_position_id, $phone_number, $public_email, $gender;
    public $userApi;
    public $localUserId;
    public $studyProgramName       = '-';
    public $functionalPositionName = '-';
    public $identifiers            = [];
    public $indexOptions           = [];

    public function mount()
    {
        $this->userApi = request()->auth;

        $localUser = User::where('email', $this->userApi->email)->first();

        if ($localUser) {
            $this->localUserId = $localUser->id;

            $profile = UserProfile::where('user_id', $localUser->id)->first();

            if ($profile) {
                $this->nidn                   = $profile->nidn;
                $this->study_program_id       = $profile->study_program_id;
                $this->functional_position_id = $profile->functional_position_id;
                $this->phone_number           = $profile->phone_number;
                $this->public_email           = $profile->public_email;
                $this->gender                 = $profile->gender;
                $this->studyProgramName       = StudyProgram::find($profile->study_program_id)?->name ?? '-';
                $this->functionalPositionName = FunctionalPosition::find($profile->functional_position_id)?->name ?? '-';
            } else {
                $this->gender = $this->userApi->gender ?? 'male';
            }
        }

        $this->indexOptions = DatabaseIndex::where('is_active', true)->get();

        $existingIds = ResearcherIdentifier::where('user_id', $this->localUserId)->get();

        if ($existingIds->count()) {
            foreach ($existingIds as $item) {
                $this->identifiers[] = [
                    'index_id'    => $item->database_index_id,
                    'author_id'   => $item->author_id,
                    'profile_url' => $item->profile_url,
                ];
            }
        } else {
            $this->identifiers[] = [
                'index_id'    => '',
                'author_id'   => '',
                'profile_url' => '',
            ];
        }
    }

    public function addIdentifier()
    {
        $this->identifiers[] = [
            'index_id'    => '',
            'author_id'   => '',
            'profile_url' => '',
        ];
    }

    public function removeIdentifier($index)
    {
        unset($this->identifiers[$index]);
        $this->identifiers = array_values($this->identifiers);
    }

    public function saveAll()
    {
        $this->validate([
            'nidn'                   => 'required|max:20',
            'study_program_id'       => 'required',
            'functional_position_id' => 'required',
            'phone_number'           => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Profil
            $genderMapped = null;
            if ($this->gender) {
                $genderMapped = (strtolower($this->gender) === 'f' || strtolower($this->gender) === 'female')
                    ? 'female'
                    : 'male';
            }

            UserProfile::updateOrCreate(
                ['user_id' => $this->localUserId],
                [
                    'study_program_id'       => $this->study_program_id,
                    'functional_position_id' => $this->functional_position_id,
                    'nidn'                   => $this->nidn,
                    'phone_number'           => $this->phone_number,
                    'public_email'           => $this->public_email,
                    'gender'                 => $genderMapped,
                    'updated_by'             => $this->localUserId,
                ]
            );

            // 2. Simpan ID Peneliti (hanya yang terisi)
            foreach ($this->identifiers as $row) {
                if (empty($row['index_id']) || empty($row['author_id'])) {
                    continue;
                }

                ResearcherIdentifier::updateOrCreate(
                    [
                        'user_id'           => $this->localUserId,
                        'database_index_id' => $row['index_id'],
                    ],
                    [
                        'author_id'   => $row['author_id'],
                        'profile_url' => $row['profile_url'],
                        'updated_by'  => $this->localUserId,
                    ]
                );
            }

            // 3. Update nama tampilan
            $this->studyProgramName       = StudyProgram::find($this->study_program_id)?->name ?? '-';
            $this->functionalPositionName = FunctionalPosition::find($this->functional_position_id)?->name ?? '-';

            DB::commit();
            $this->dispatch('showSuccessAlert', 'Profil dan ID Peneliti berhasil disimpan!');
            return redirect()->route('index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showErrorAlert', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('features.dosen.profile.index-livewire', [
            'listProdi'    => StudyProgram::all(),
            'listJabfung'  => FunctionalPosition::all(),
            'indexOptions' => $this->indexOptions,
        ]);
    }
}



