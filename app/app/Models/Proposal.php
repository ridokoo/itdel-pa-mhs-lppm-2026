<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'category_id',
        'scheme_group_id',
        'scheme_type_id',
        'scope_id',
        'period_id',
        'leader_id',
        'status_id',
        'title',
        'summary',
        'target_tkt_id',   // <--- WAJIB ADA DI SINI
        'duration_months',
        'total_budget',
        'proposal_file',
        'decision_note',
        'reviewer_note',
        'submitted_at',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // ============================================================
    // RELASI (Inilah yang menyebabkan error jika tidak ada)
    // ============================================================

    // Relasi ke Kelompok Skema (Yang menyebabkan error tadi)
    public function schemeGroup()
    {
        return $this->belongsTo(SchemeGroup::class, 'scheme_group_id');
    }

    // Relasi lainnya agar fitur "Detail" Operator jalan semua
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function schemeType()
    {
        return $this->belongsTo(SchemeType::class, 'scheme_type_id');
    }

    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

    public function period()
    {
        return $this->belongsTo(ProposalPeriod::class, 'period_id');
    }

    public function status()
    {
        return $this->belongsTo(ProposalStatus::class, 'status_id');
    }

    // public function statusFinalUsulan()
    // {
    //     return $this->belongsTo(ProposalStatus::class, 'status_final_id');
    // }

    // public function statusFinalResult()
    // {
    //     return $this->belongsTo(ProposalStatus::class, 'status_final_id');
    // }

    public function statusFinal()
    {
        return $this->belongsTo(ProposalStatus::class, 'status_final_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function proposalMembers()
    {
        return $this->hasMany(ProposalMember::class, 'proposal_id', 'id');
    }

    public function reviewers()
    {
        return $this->hasMany(ProposalReviewer::class, 'proposal_id');
    }

    public function budgetItems()
    {
        return $this->hasMany(BudgetItem::class, 'proposal_id', 'id');
    }

    // 2. Relasi ke Tabel Target Luaran (proposal_output_targets)
    public function proposalOutputTargets()
    {
        return $this->hasMany(ProposalOutputTarget::class, 'proposal_id', 'id');
    }

    // 3. Relasi ke Tabel Mitra (proposal_partners)
    public function partners()
    {
        return $this->hasMany(ProposalPartner::class, 'proposal_id', 'id');
    }

    public function outputTargets()
    {
        // Merujuk ke model ProposalOutputTarget
        // Parameter kedua adalah Foreign Key di tabel proposal_output_targets
        return $this->hasMany(ProposalOutputTarget::class, 'proposal_id', 'id');
    }

    public function progressReports()
    {
        return $this->hasMany(\App\Models\ProgressReport::class);
    }

    public function finalReport()
    {
        return $this->hasOne(FinalReport::class);
    }

    /**
     * Cek apakah proposal sudah bisa submit laporan akhir.
     * Syarat: laporan kemajuan sudah approved.
     */
    public function canSubmitFinalReport(): bool
    {
        // Sesuaikan dengan relasi laporan kemajuan yang ada di proyekmu
        return $this->progressReports()
            ->whereHas('status', function ($q) {
                $q->whereLike('name', '%approved%')
                    ->orWhereLike('name', '%setuju%');
            })
            ->exists();
    }
}



