<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scope extends Model
{
    use HasFactory;

    protected $table = 'scopes';

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

    // Relasi ke Proposal (untuk proteksi hapus)
    //     public function proposals()
    //     {
    //         return $this->hasMany(Proposal::class, 'scope_id');
    //     }
}



