<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutputTargetDetail extends Model
{
    use HasFactory;

    protected $table = 'output_target_details';

    protected $fillable = [
        'output_target_category_id',
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Detail ini milik satu Kategori (Jurnal, Buku, dll)
    public function category()
    {
        return $this->belongsTo(OutputTargetCategory::class, 'output_target_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



