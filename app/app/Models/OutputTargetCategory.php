<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutputTargetCategory extends Model
{
    use HasFactory;

    protected $table = 'output_target_categories';

    protected $fillable = [
        'output_target_type_id',
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Kategori ini milik satu Jenis (Wajib/Tambahan)
    public function type()
    {
        return $this->belongsTo(OutputTargetType::class, 'output_target_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



