<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalPartner extends Model
{
    protected $table = 'proposal_partners';

    protected $fillable = [
        'proposal_id',
        'identity_number',
        'name',
        'address',
        'institution',
        'qualification',
        'email',
        'role_description',
        'created_by',
        'updated_by',
        'is_active'
    ];
}



