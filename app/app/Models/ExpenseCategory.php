<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
        'description',
        'percentage_min',
        'percentage_max',
        'display_order',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // Cast decimal agar terbaca sebagai float di PHP
    protected $casts = [
        'percentage_min' => 'float',
        'percentage_max' => 'float',
        'display_order' => 'integer'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



