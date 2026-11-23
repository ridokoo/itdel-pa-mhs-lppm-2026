<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    protected $table = 'progress_reports';

    protected $fillable = [
        'proposal_id',
        'status_id',
        'total_spent_amount',
        'integration_plan',
        'expense_proof_file',
        'logbook_file',
        'progress_report_file',
        'submitted_at',
        'created_by',
        'updated_by',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function status()
    {
        return $this->belongsTo(ProposalStatus::class, 'status_id');
    }

    public function outputs()
    {
        return $this->hasMany(ProgressReportOutput::class);
    }

    public function budgets()
    {
        return $this->hasMany(ProgressReportBudget::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProgressReportReview::class);
    }
}



