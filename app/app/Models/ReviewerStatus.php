<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewerStatus extends Model
{
    use HasFactory;

    protected $table = 'reviewer_status'; 

    public $timestamps = false;

    protected $fillable = ['name'];

    // Relasi ke penugasan (untuk proteksi hapus)
    public function assignments()
    {
        return $this->hasMany(ProposalReviewer::class, 'status');
    }
}



