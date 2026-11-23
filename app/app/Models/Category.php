<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

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



