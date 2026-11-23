<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalMember extends Model
{
    use HasFactory;

    protected $table = 'proposal_members';

    protected $fillable = [
        'proposal_id',
        'user_id',
        'member_status_id',
        'role_in_proposal', // leader, dosen, mahasiswa, staff
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Anggota merujuk ke satu Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    // RELASI: Anggota merujuk ke satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // RELASI: Status Anggota
    public function status()
    {
        return $this->belongsTo(ProposalMemberStatus::class, 'member_status_id');
    }
}



