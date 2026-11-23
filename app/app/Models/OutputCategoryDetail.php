<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutputCategoryDetail extends Model
{
    use HasFactory;

    protected $table = 'output_category_details';

    protected $fillable = [
        'scheme_type_id',
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Luaran merujuk ke satu Tipe Skema
    public function schemeType()
    {
        return $this->belongsTo(SchemeType::class, 'scheme_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



