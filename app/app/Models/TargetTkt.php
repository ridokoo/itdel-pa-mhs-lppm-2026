<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetTkt extends Model
{
    use HasFactory;

    protected $table = 'target_tkts';

    protected $fillable = [
        'level',
        'name',
        'description',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



