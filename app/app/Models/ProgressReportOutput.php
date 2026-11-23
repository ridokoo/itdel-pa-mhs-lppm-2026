<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressReportOutput extends Model
{
    protected $table = 'progress_report_outputs';

    protected $fillable = [
        'progress_report_id',
        'proposal_output_target_id',
        'output_target_status_id',
        'achievement_description',
        'evidence_file',
    ];

    public function progressReport()
    {
        return $this->belongsTo(ProgressReport::class);
    }

    public function proposalOutputTarget()
    {
        return $this->belongsTo(ProposalOutputTarget::class);
    }

    public function outputTargetStatus()
    {
        return $this->belongsTo(OutputTargetStatus::class, 'output_target_status_id');
    }
}



