<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressReportBudget extends Model
{
    protected $table = 'progress_report_budgets';

    protected $fillable = [
        'progress_report_id',
        'budget_item_id',
        'amount_spent',
    ];

    public function progressReport()
    {
        return $this->belongsTo(ProgressReport::class);
    }

    public function budgetItem()
    {
        return $this->belongsTo(BudgetItem::class);
    }
}



