<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DatabaseIndex extends Model
{
    use HasFactory;

    protected $table = 'database_indexes';

    protected $fillable = [
        'name',
        'base_url',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



