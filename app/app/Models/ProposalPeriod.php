<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalPeriod extends Model
{
    use HasFactory;

    protected $table = 'proposal_periods';

    protected $fillable = [
        'year',
        'name',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // Mengonversi string tanggal dari database menjadi objek Carbon otomatis
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'period_id');
    }
}



