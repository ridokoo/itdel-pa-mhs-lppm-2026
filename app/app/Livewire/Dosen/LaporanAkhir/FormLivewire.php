<?php

namespace App\Livewire\Dosen\LaporanAkhir;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\{DB, Storage};
use App\Models\{
    User,
    Proposal,
    FinalReport,
    FinalReportOutput,
    FinalReportBudget,
    ProposalStatus,
    OutputTargetStatus,
};

class FormLivewire extends Component
{
    use WithFileUploads;

    public $proposal_id;
    public $id_laporan;
    public $localUser;
    public $proposal;

    public $total_spent_amount = 0;
    public $integration_plan   = '';

    public $expense_proof_file;
    public $logbook_file;
    public $final_report_file;

    public $existing_expense_proof_file;
    public $existing_logbook_file;
    public $existing_final_report_file;

    public $outputs = [];
    public $budgets = [];

    public $outputTargetStatuses = [];
    public $isEdit               = false;
    public $isReadOnly           = false;

    // Tambahkan variabel untuk computed property
    public $totalBudget      = 0;
    public $totalSpentAmount = 0;
    public $spentPercentage  = 0;

    public function mount($proposal_id = null, $id_laporan = null)
    {
        $this->proposal_id = $proposal_id;
        $this->id_laporan  = $id_laporan;
        $this->isEdit      = !is_null($id_laporan);

        // Gunakan auth()->user() yang lebih aman
        $this->localUser   = auth()->user() ?? User::where('email', request()->auth?->email)->first();

        $this->proposal = Proposal::with([
            'outputTargets.outputTargetType',
            'outputTargets.outputTargetCategory',
            'budgetItems.expenseCategory',
            'status'
        ])->findOrFail($proposal_id);

        $this->outputTargetStatuses = OutputTargetStatus::orderBy('name')->get();

        if ($this->isEdit) {
            $finalReport = FinalReport::with(['outputs', 'budgets', 'status'])->find($id_laporan);

            if ($finalReport && $finalReport->proposal_id == $this->proposal_id) {

                $statusNama = strtolower($finalReport->status?->name ?? '');

                /**
                 * LOGIKA PERBAIKAN:
                 * Form akan TERKUNCI (isReadOnly = true) jika:
                 * 1. Status mengandung kata 'submitted' (Menunggu Review)
                 * 2. Status mengandung kata 'approved', 'disetujui', atau 'selesai'
                 * 3. Parameter 'mode' di URL adalah 'view'
                 */
                $isSubmitted = str_contains($statusNama, 'submitted');
                $isApproved  = str_contains($statusNama, 'approved') || str_contains($statusNama, 'setuju') || str_contains($statusNama, 'selesai');

                if ($isSubmitted || $isApproved || request()->query('mode') === 'view') {
                    $this->isReadOnly = true;
                }

                // Jika status mengandung kata 'revisi' atau 'draft', maka isReadOnly tetap FALSE (bisa edit)

                $this->loadExistingData($finalReport);
            } else {
                $this->isEdit     = false;
                $this->id_laporan = null;
                $this->loadDefaultData();
            }
        } else {
            $this->loadDefaultData();
        }

        $this->calculateTotalSpent();
    }

    private function loadExistingData(FinalReport $report): void
    {
        $this->total_spent_amount = $report->total_spent_amount;
        $this->integration_plan   = $report->integration_plan;

        $this->existing_expense_proof_file = $report->expense_proof_file;
        $this->existing_logbook_file       = $report->logbook_file;
        $this->existing_final_report_file  = $report->final_report_file;

        $this->outputs = $this->proposal->outputTargets->map(function ($target) use ($report) {
            $existing = $report->outputs->firstWhere('proposal_output_target_id', $target->id);
            return [
                'proposal_output_target_id' => $target->id,
                'type_name'                 => $target->outputTargetType?->name ?? '-',
                'category_name'             => $target->outputTargetCategory?->name ?? '-',
                'description'               => $target->description ?? '-',
                'output_target_status_id'   => $existing?->output_target_status_id ?? '',
                'achievement_description'   => $existing?->achievement_description ?? '',
                'evidence_file'             => null,
                'existing_evidence_file'    => $existing?->evidence_file,
                'output_id'                 => $existing?->id,
            ];
        })->toArray();

        $this->budgets = $this->proposal->budgetItems->map(function ($item) use ($report) {
            $existing = $report->budgets->firstWhere('budget_item_id', $item->id);
            return [
                'budget_item_id'  => $item->id,
                'item_name'       => $item->item_name,
                'component'       => $item->component ?? '-',
                'category_name'   => $item->expenseCategory?->name ?? '-',
                'unit_price'      => $item->unit_price,
                'volume'          => $item->volume,
                'unit'            => $item->unit,
                'total_plan'      => $item->total,
                'amount_spent'    => $existing?->amount_spent ?? 0,
                'budget_id'       => $existing?->id,
            ];
        })->toArray();
    }

    private function loadDefaultData(): void
    {
        $this->outputs = $this->proposal->outputTargets->map(fn($target) => [
            'proposal_output_target_id' => $target->id,
            'type_name'                 => $target->outputTargetType?->name ?? '-',
            'category_name'             => $target->outputTargetCategory?->name ?? '-',
            'description'               => $target->description ?? '-',
            'output_target_status_id'   => '',
            'achievement_description'   => '',
            'evidence_file'             => null,
            'existing_evidence_file'    => null,
            'output_id'                 => null,
        ])->toArray();

        $this->budgets = $this->proposal->budgetItems->map(fn($item) => [
            'budget_item_id'  => $item->id,
            'item_name'       => $item->item_name,
            'component'       => $item->component ?? '-',
            'category_name'   => $item->expenseCategory?->name ?? '-',
            'unit_price'      => $item->unit_price,
            'volume'          => $item->volume,
            'unit'            => $item->unit,
            'total_plan'      => $item->total,
            'amount_spent'    => 0,
            'budget_id'       => null,
        ])->toArray();
    }

    public function calculateTotalSpent(): void
    {
        $this->totalBudget      = (float) ($this->proposal->total_budget ?? 0);
        $this->totalSpentAmount = collect($this->budgets)->sum(fn($r) => (float) ($r['amount_spent'] ?? 0));
        $this->spentPercentage  = $this->totalBudget > 0
            ? round(($this->totalSpentAmount / $this->totalBudget) * 100, 2)
            : 0;

        // Update field utama untuk database
        $this->total_spent_amount = $this->totalSpentAmount;
    }

    public function updated($prop): void
    {
        if (str_contains($prop, 'budgets')) {
            $this->calculateTotalSpent();
        }
    }

    public function saveDraft()
    {
        $this->save(submitFinal: false);
    }

    public function submit()
    {
        $this->save(submitFinal: true);
    }

    private function save(bool $submitFinal = false)
    {
        $this->validate([
            'integration_plan'                            => 'required|string|min:10',
            'outputs.*.output_target_status_id'           => 'required',
            'outputs.*.achievement_description'           => 'required|string|min:5',
            'budgets.*.amount_spent'                      => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $expensePath = $this->handleUpload($this->expense_proof_file, 'laporan-akhir/bukti', $this->existing_expense_proof_file);
            $logbookPath = $this->handleUpload($this->logbook_file,       'laporan-akhir/logbook', $this->existing_logbook_file);
            $reportPath  = $this->handleUpload($this->final_report_file,  'laporan-akhir/dokumen', $this->existing_final_report_file);

            // Jika dia revisi, maka status saat save draft tetap revisi, tapi saat submit jadi submitted
            $statusDraft = ProposalStatus::where('name', 'like', '%draft%')->first();
            $statusAju   = ProposalStatus::where('name', 'like', '%submitted%')->first();

            $payload = [
                'proposal_id'         => $this->proposal_id,
                'total_spent_amount'  => $this->total_spent_amount,
                'integration_plan'    => $this->integration_plan,
                'expense_proof_file'  => $expensePath,
                'logbook_file'        => $logbookPath,
                'final_report_file'   => $reportPath,
                'updated_by'          => $this->localUser->id,
            ];

            if ($submitFinal) {
                $payload['status_id'] = $statusAju->id ?? 2;
                $payload['submitted_at'] = now();
            } else {
                // Jika sedang edit, jangan timpa status aslinya (misal aslinya Revisi jangan jadi Draft)
                if (!$this->isEdit) {
                    $payload['status_id'] = $statusDraft->id ?? 1;
                }
            }

            $report = FinalReport::updateOrCreate(
                ['id' => $this->id_laporan],
                array_merge($payload, ['created_by' => $this->localUser->id])
            );

            $this->id_laporan = $report->id;

            // Sync outputs & budgets (Logika sama seperti sebelumnya...)
            foreach ($this->outputs as $i => $row) {
                $evidencePath = $this->handleUpload($row['evidence_file'] ?? null, 'laporan-akhir/evidence', $row['existing_evidence_file'] ?? null);

                FinalReportOutput::updateOrCreate(
                    ['id' => $row['output_id'] ?? null],
                    [
                        'final_report_id'           => $report->id,
                        'proposal_output_target_id' => $row['proposal_output_target_id'],
                        'output_target_status_id'   => $row['output_target_status_id'] ?: null,
                        'achievement_description'   => $row['achievement_description'] ?? null,
                        'evidence_file'             => $evidencePath,
                        'updated_by'                => $this->localUser->id,
                        'created_by'                => $this->localUser->id,
                    ]
                );
            }

            FinalReportBudget::where('final_report_id', $report->id)->delete();
            foreach ($this->budgets as $row) {
                FinalReportBudget::create([
                    'final_report_id' => $report->id,
                    'budget_item_id'  => $row['budget_item_id'],
                    'amount_spent'    => (float) ($row['amount_spent'] ?? 0),
                ]);
            }

            DB::commit();

            if ($submitFinal) {
                return redirect()->route('dosen.laporan-akhir.index')->with('success', 'Laporan berhasil diajukan!');
            }

            $this->dispatch('showSuccessAlert', 'Draft berhasil disimpan!');
            $this->isEdit = true;
            $this->loadExistingData($report);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showErrorAlert', 'Gagal: ' . $e->getMessage());
        }
    }

    private function handleUpload($file, string $folder, ?string $oldPath): ?string
    {
        if (!$file) return $oldPath;
        if ($oldPath) Storage::disk('public')->delete($oldPath);
        return $file->storeAs($folder, time() . '_' . $file->getClientOriginalName(), 'public');
    }

    public function render()
    {
        return view('features.dosen.laporan-akhir.form');
    }
}



