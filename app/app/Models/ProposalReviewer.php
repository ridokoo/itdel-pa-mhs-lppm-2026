<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalReviewer extends Model
{
    use HasFactory;

    // Nama tabel harus sesuai dengan yang di Postgres
    protected $table = 'proposal_reviewers';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'assigned_by',
        'reviewer_status_id',
        'notes',
        'assigned_at',
        'completed_at'
    ];

    // Nonaktifkan timestamps standar Laravel jika Anda menggunakan nama kolom kustom
    public $timestamps = false;

    // ============================================================
    // RELASI
    // ============================================================

    // Relasi ke Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    // Relasi ke User (Dosen yang menjadi Reviewer)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Relasi ke User (Orang yang menugaskan)
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewerStatus()
    {
        return $this->belongsTo(ReviewerStatus::class, 'status');
    }
}



