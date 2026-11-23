<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalReportOutput extends Model
{
    protected $fillable = [
        'final_report_id',
        'proposal_output_target_id',
        'output_target_status_id',
        'achievement_description',
        'evidence_file',
        'created_by',
        'updated_by',
    ];

    public function finalReport(): BelongsTo
    {
        return $this->belongsTo(FinalReport::class);
    }

    public function outputTarget(): BelongsTo
    {
        return $this->belongsTo(ProposalOutputTarget::class, 'proposal_output_target_id');
    }

    public function outputTargetStatus(): BelongsTo
    {
        return $this->belongsTo(OutputTargetStatus::class, 'output_target_status_id');
    }
}



