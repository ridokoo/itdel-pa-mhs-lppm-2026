<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['title', 'content', 'image', 'event_date', 'status', 'is_active', 'created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}



