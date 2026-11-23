<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchemeGroup extends Model
{
    use HasFactory;

    protected $table = 'scheme_groups';

    protected $fillable = [
        'category_id',
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Kelompok Skema merujuk ke satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // RELASI: Siapa yang membuat data ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



