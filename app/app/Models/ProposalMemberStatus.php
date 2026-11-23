<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalMemberStatus extends Model
{
    use HasFactory;

    protected $table = 'proposal_member_status';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Siapa yang membuat data ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



