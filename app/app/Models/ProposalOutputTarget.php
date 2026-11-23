<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalOutputTarget extends Model
{
    protected $table = 'proposal_output_targets';

    protected $fillable = [
        'proposal_id',
        'output_target_type_id',
        'output_target_category_id',
        'output_target_detail_id',
        'output_target_status_id',
        'description',
        'created_by',
    ];

    // ✅ Relasi yang dibutuhkan oleh LaporanKemajuanFormLivewire
    public function outputTargetType()
    {
        return $this->belongsTo(OutputTargetType::class, 'output_target_type_id');
    }

    public function outputTargetCategory()
    {
        return $this->belongsTo(OutputTargetCategory::class, 'output_target_category_id');
    }

    public function outputTargetDetail()
    {
        return $this->belongsTo(OutputTargetDetail::class, 'output_target_detail_id');
    }

    public function outputTargetStatus()
    {
        return $this->belongsTo(OutputTargetStatus::class, 'output_target_status_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
    public function category()
{
    return $this->belongsTo(\App\Models\OutputTargetCategory::class, 'category_id');
}
}



