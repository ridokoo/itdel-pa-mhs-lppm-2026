<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public $timestamps = false; // ← tambahkan ini

    protected $fillable = [
        'name',
        'is_active',
        'created_by',
        'updated_by'
    ];
}



