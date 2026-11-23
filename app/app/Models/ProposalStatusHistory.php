<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalStatusHistory extends Model
{
    protected $table = 'proposal_status_history';

    protected $fillable = [
        'proposal_id',
        'proposal_status_id',
        'changed_by',
        'note',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function proposalStatus()
    {
        return $this->belongsTo(ProposalStatus::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}



