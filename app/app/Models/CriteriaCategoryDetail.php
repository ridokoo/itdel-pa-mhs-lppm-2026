<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CriteriaCategoryDetail extends Model
{
    use HasFactory;
    protected $table = 'criteria_category_details';
    protected $fillable = [
        'scheme_type_id',
        'min_budget',
        'max_budget',
        'min_members',
        'max_members',
        'min_functional_position_id',
        'allow_s2',
        'allow_s3',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function functionalPosition()
    {
        // Parameter kedua adalah nama kolom di tabel ini (min_functional_position_id)
        // Parameter ketiga adalah nama kolom ID di tabel tujuan
        return $this->belongsTo(FunctionalPosition::class, 'min_functional_position_id', 'id');
    }

    // RELASI: Kriteria merujuk ke satu Tipe Skema
    public function schemeType()
    {
        return $this->belongsTo(SchemeType::class, 'scheme_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



