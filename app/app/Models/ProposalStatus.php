<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalStatus extends Model
{
    use HasFactory;

    protected $table = 'proposal_status';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Siapa yang membuat status ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // RELASI: Proposal yang menggunakan status ini
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'status_id');
    }
}



