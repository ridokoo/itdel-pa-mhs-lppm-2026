<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchemeType extends Model
{
    use HasFactory;

    protected $table = 'scheme_types';

    protected $fillable = [
        'scheme_group_id',
        'name',
        'tkt_min',
        'tkt_max',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELASI: Tipe Skema merujuk ke satu Kelompok Skema
    public function schemeGroup()
    {
        return $this->belongsTo(SchemeGroup::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function criteria()
    {
        return $this->hasMany(CriteriaCategoryDetail::class, 'scheme_type_id');
    }


    public function mandatoryOutputs()
    {
        return $this->hasMany(OutputCategoryDetail::class, 'scheme_type_id');
    }

    public function outputGuides()
    {
        return $this->hasMany(OutputCategoryDetail::class, 'scheme_type_id');
    }
}



