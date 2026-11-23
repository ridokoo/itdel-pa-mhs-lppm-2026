<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $fillable = [
        'name', 'contact_value', 'icon', 'image',
        'display_order', 'is_active', 'created_by', 'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->created_by = $model->updated_by = Auth::id());
        static::updating(fn($model) => $model->updated_by = Auth::id());
    }
}



