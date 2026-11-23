<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalReportBudget extends Model
{
    protected $fillable = [
        'final_report_id',
        'budget_item_id',
        'amount_spent',
    ];

    protected $casts = [
        'amount_spent' => 'decimal:2',
    ];

    public function finalReport(): BelongsTo
    {
        return $this->belongsTo(FinalReport::class);
    }

    public function budgetItem(): BelongsTo
    {
        return $this->belongsTo(BudgetItem::class);
    }
}



