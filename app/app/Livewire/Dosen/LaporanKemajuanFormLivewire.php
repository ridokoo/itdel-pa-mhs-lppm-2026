<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{
    User,
    Proposal,
    ProposalStatus,
    ProgressReport,
    ProgressReportOutput,
    ProgressReportBudget,
    OutputTargetStatus
};
use Illuminate\Support\Facades\{DB, Storage};
use Livewire\Attributes\Url;
use App\Services\NotificationService;

class LaporanKemajuanFormLivewire extends Component
{
    use WithFileUploads;

    #[Url]
    public $currentStep = 1;
    public $isReadOnly  = false;
    public $isEdit      = false;
    public $isAutoSave  = false;

    public $proposal_id;
    public $id_laporan;
    public $localUser;
    public $proposal;

    public $budgetRealizations = [];
    public $totalSpentAmount   = 0;
    public $spentPercentage    = 0;
    public $totalBudget        = 0;

    public $outputRealizations = [];

    public $integration_plan;
    public $expense_proof_file;
    public $logbook_file;
    public $progress_report_file;
    public $existing_expense_proof;
    public $existing_logbook;
    public $existing_progress_report;

    public $outputTargetStatuses = [];

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    public function mount($proposal_id, $id_laporan = null)
    {
        $this->proposal_id = $proposal_id;
$this->localUser = auth()->user();

        $this->outputTargetStatuses = OutputTargetStatus::orderBy('name')->get()->toArray();

        $this->proposal = Proposal::with([
            'outputTargets.outputTargetType',
            'outputTargets.outputTargetCategory',
            'budgetItems.expenseCategory',
        ])->findOrFail($this->proposal_id);

        $this->totalBudget = (float) $this->proposal->total_budget;

        foreach ($this->proposal->budgetItems as $item) {
            $this->budgetRealizations[$item->id] = [
                'budget_item_id' => $item->id,
                'item_name'      => $item->item_name,
                'component'      => $item->component ?? '-',
                'unit_price'     => $item->unit_price,
                'volume'         => $item->volume,
                'unit'           => $item->unit,
                'total_plan'     => $item->total,
                'amount_spent'   => 0,
                'category_name'  => $item->expenseCategory?->name ?? '-',
            ];
        }

        foreach ($this->proposal->outputTargets as $out) {
            $this->outputRealizations[] = [
                'proposal_output_target_id' => $out->id,
                'type_name'                 => $out->outputTargetType?->name ?? '-',
                'category_name'             => $out->outputTargetCategory?->name ?? '-',
                'description'               => $out->description ?? '-',
                'output_target_status_id'   => '',
                'achievement_description'   => '',
                'existing_evidence_file'    => null,
            ];
        }


        // ✅ Validasi id_laporan: pastikan record benar-benar ada di DB
        if ($id_laporan) {
            $laporan = ProgressReport::with(['outputs', 'budgets'])->find($id_laporan);

            if ($laporan && $laporan->proposal_id == $this->proposal_id) {
                $this->id_laporan = $id_laporan;
                $this->isEdit     = true;

                if (request()->query('mode') === 'view') {
                    $this->isReadOnly  = true;
                    $this->currentStep = 4;
                }

                $this->loadDataToProperties($laporan);
            } else {
                // ✅ id_laporan tidak valid — reset agar tidak error
                $this->id_laporan  = null;
                $this->isEdit      = false;
                $this->currentStep = 1;
            }
        } else {
            $this->id_laporan = null;
            $this->isEdit     = false;
        }
    }

    // =========================================================================
    // LOAD DATA EDIT
    // =========================================================================

    private function loadDataToProperties(ProgressReport $laporan): void
    {
        $this->integration_plan           = $laporan->integration_plan;
        $this->existing_expense_proof     = $laporan->expense_proof_file;
        $this->existing_logbook           = $laporan->logbook_file;
        $this->existing_progress_report   = $laporan->progress_report_file;

        foreach ($laporan->budgets as $b) {
            if (isset($this->budgetRealizations[$b->budget_item_id])) {
                $this->budgetRealizations[$b->budget_item_id]['amount_spent'] = $b->amount_spent;
            }
        }
        $this->calculateTotalSpent();

        foreach ($laporan->outputs as $out) {
            foreach ($this->outputRealizations as &$row) {
                if ($row['proposal_output_target_id'] == $out->proposal_output_target_id) {
                    $row['output_target_status_id']  = $out->output_target_status_id;
                    $row['achievement_description']  = $out->achievement_description;
                    $row['existing_evidence_file']   = $out->evidence_file;
                    break;
                }
            }
            unset($row);
        }
    }

    // =========================================================================
    // NAVIGASI STEP
    // =========================================================================

    public function nextStep(int $step): void
    {
        if (!$this->isReadOnly) {
            $this->isAutoSave = true;
            $this->saveLaporan();
            $this->isAutoSave = false;
        }

        // Cek setelah save — id_laporan harus sudah terisi
        if ($step === 4 && !$this->id_laporan) {
            $this->dispatch('showErrorAlert', 'Gagal menyimpan draf. Coba lagi.');
            return;
        }

        $this->currentStep = $step;
    }

    // =========================================================================
    // SAVE
    // =========================================================================

    public function saveLaporan(): void
    {
        DB::beginTransaction();
        try {
            $expensePath = $this->existing_expense_proof;
            $logbookPath = $this->existing_logbook;
            $reportPath  = $this->existing_progress_report;

            if ($this->expense_proof_file) {
                $expensePath = $this->handleUpload(
                    $this->expense_proof_file,
                    'laporan/bukti',
                    $expensePath
                );
                $this->expense_proof_file = null;
            }
            if ($this->logbook_file) {
                $logbookPath = $this->handleUpload(
                    $this->logbook_file,
                    'laporan/logbook',
                    $logbookPath
                );
                $this->logbook_file = null;
            }
            if ($this->progress_report_file) {
                $reportPath = $this->handleUpload(
                    $this->progress_report_file,
                    'laporan/dokumen',
                    $reportPath
                );
                $this->progress_report_file = null;
            }

            $this->calculateTotalSpent();

            $statusDraft = ProposalStatus::where('name', 'like', '%draft%')->first();

            $payload = [
                'proposal_id'          => $this->proposal_id,
                'status_id'            => $statusDraft->id ?? 1,
                'total_spent_amount'   => $this->totalSpentAmount,
                'integration_plan'     => $this->integration_plan,
                'expense_proof_file'   => $expensePath,
                'logbook_file'         => $logbookPath,
                'progress_report_file' => $reportPath,
                'updated_by'           => $this->localUser->id,
            ];

            // ✅ Gunakan find() bukan findOrFail() untuk cek dulu
            $existingLaporan = $this->id_laporan
                ? ProgressReport::find($this->id_laporan)
                : null;

            if ($existingLaporan) {
                // UPDATE
                $existingLaporan->update($payload);
                $laporan = $existingLaporan;
            } else {
                // CREATE BARU
                $payload['created_by'] = $this->localUser->id;
                $laporan               = ProgressReport::create($payload);
                $this->id_laporan      = $laporan->id;
                $this->isEdit          = true;
            }

            $this->existing_expense_proof   = $expensePath;
            $this->existing_logbook         = $logbookPath;
            $this->existing_progress_report = $reportPath;

            $this->syncChildren($laporan);

            DB::commit();

            if (!$this->isAutoSave) {
                $this->dispatch('showSuccessAlert', 'Laporan kemajuan berhasil disimpan!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if (!$this->isAutoSave) {
                $this->dispatch('showErrorAlert', 'Gagal menyimpan: ' . $e->getMessage());
            }
            \Log::error('saveLaporan error: ' . $e->getMessage(), [
                'proposal_id' => $this->proposal_id,
                'id_laporan'  => $this->id_laporan,
            ]);
        }
    }

    private function syncChildren(ProgressReport $laporan): void
    {
        ProgressReportBudget::where('progress_report_id', $laporan->id)->delete();
        foreach ($this->budgetRealizations as $row) {
            ProgressReportBudget::create([
                'progress_report_id' => $laporan->id,
                'budget_item_id'     => $row['budget_item_id'],
                'amount_spent'       => (float) ($row['amount_spent'] ?? 0),
            ]);
        }

        ProgressReportOutput::where('progress_report_id', $laporan->id)->delete();
        foreach ($this->outputRealizations as $row) {
            ProgressReportOutput::create([
                'progress_report_id'        => $laporan->id,
                'proposal_output_target_id' => $row['proposal_output_target_id'],
                'output_target_status_id'   => $row['output_target_status_id'] ?: null,
                'achievement_description'   => $row['achievement_description'] ?? null,
                'evidence_file'             => $row['existing_evidence_file'] ?? null,
            ]);
        }
    }

    // =========================================================================
    // SUBMIT FINAL
    // =========================================================================
public function finalSubmit()
{
    if (!$this->id_laporan) {
        $this->dispatch('showErrorAlert', 'ID laporan tidak ditemukan.');
        return;
    }

    $laporan = ProgressReport::with('proposal')->find($this->id_laporan);
    if (!$laporan) {
        $this->dispatch('showErrorAlert', 'Data laporan tidak ditemukan.');
        return;
    }

    // Cari status submitted — sesuaikan nama dengan isi DB kamu
    $statusAju = ProposalStatus::where('name', 'Submitted')->first()
                ?? ProposalStatus::where('name', 'like', '%ubmitted%')->first();

    // ✅ Sesuai data di database: "Submitted"
    $statusAju = ProposalStatus::where('name', 'like', '%submitted%')->first();
    if (!$statusAju) {
        $this->dispatch('showErrorAlert', 'Status "Submitted" tidak ditemukan. Cek tabel proposal_statuses.');
        return;
    }

    try {
        $laporan->update([
            'status_id'    => $statusAju->id,
            'submitted_at' => now(),
        ]);

        // Notifikasi reviewer
        try {
            $reviewers = User::whereHas('roles', fn($q) =>
                $q->where('name', 'like', '%reviewer%')
            )->get();



            foreach ($reviewers as $user) {
                app(NotificationService::class)->send(
                    userId: $user->id,
                    title: 'Laporan Kemajuan Baru',
                    message: "Laporan kemajuan untuk '{$laporan->proposal->title}' telah diajukan.",
                    options: [
                        'url'        => route('reviewer.dashboard'),
                        'icon'       => 'ph-bell-ring',
                        'event_type' => 'progress_report_submitted',
                        'roles'      => ['reviewer'],
                    ]
                );
            }
        } catch (\Exception $e) {
            \Log::warning('Notifikasi gagal: ' . $e->getMessage());
        }

        return redirect()->route('dosen.laporan-kemajuan.index', [
            'categoryId' => $this->proposal->category_id,
        ]);

    } catch (\Exception $e) {
        \Log::error('finalSubmit error: ' . $e->getMessage());
        $this->dispatch('showErrorAlert', 'Gagal mengajukan: ' . $e->getMessage());
    }
}
    // =========================================================================
    // HELPERS
    // =========================================================================

    private function handleUpload($file, string $folder, ?string $oldPath): string
    {
        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs($folder, $filename, 'public');
    }

    public function calculateTotalSpent(): void
    {
        $this->totalSpentAmount = collect($this->budgetRealizations)
            ->sum(fn($row) => (float) ($row['amount_spent'] ?? 0));

        $this->spentPercentage = $this->totalBudget > 0
            ? round(($this->totalSpentAmount / $this->totalBudget) * 100, 2)
            : 0;
    }

    public function updated(string $propertyName): void
    {
        if (str_contains($propertyName, 'budgetRealizations')) {
            $this->calculateTotalSpent();
        }
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        return view('features.dosen.laporan.form-livewire', [
            'proposal'             => $this->proposal,
            'outputTargetStatuses' => $this->outputTargetStatuses,
        ]);
    }
}



