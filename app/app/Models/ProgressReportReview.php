<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressReportReview extends Model
{
    protected $table = 'progress_report_reviews';

    protected $fillable = [
        'progress_report_id',
        'reviewer_id',
        'status_id',
        'review_notes',
        'created_by',
        'updated_by',
    ];

    public function progressReport()
    {
        return $this->belongsTo(ProgressReport::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function status()
    {
        return $this->belongsTo(ProposalStatus::class, 'status_id');
    }
}



