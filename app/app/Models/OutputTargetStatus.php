<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutputTargetStatus extends Model
{
    use HasFactory;

    protected $table = 'output_target_statuses';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



