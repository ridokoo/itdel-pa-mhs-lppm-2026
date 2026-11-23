<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinalReport extends Model
{
    protected $fillable = [
        'proposal_id',
        'status_id',
        'total_spent_amount',
        'integration_plan',
        'expense_proof_file',
        'logbook_file',
        'final_report_file',
        'submitted_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'total_spent_amount' => 'decimal:2',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProposalStatus::class, 'status_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(FinalReportOutput::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(FinalReportBudget::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(FinalReportReview::class);
    }
}



