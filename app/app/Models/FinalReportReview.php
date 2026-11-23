<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalReportReview extends Model
{
    protected $fillable = [
        'final_report_id',
        'reviewer_id',
        'status_id',
        'review_notes',
        'created_by',
        'updated_by',
    ];

    public function finalReport(): BelongsTo
    {
        return $this->belongsTo(FinalReport::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProposalStatus::class, 'status_id');
    }
}



