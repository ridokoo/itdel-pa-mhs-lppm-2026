<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $table = 'budget_items';

    protected $fillable = [
        'proposal_id',
        'expense_category_id',
        'component',
        'item_name',
        'volume',
        'unit',
        'unit_price',
        'total',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}



