<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Profile extends Model
{
    protected $table = 'profiles';

    // created_at dan updated_at tidak perlu masuk sini
    protected $fillable = [
        'title',
        'content',
        'content_type',
        'image',
        'display_order',
        'status',
        'is_active',
        'created_by',
        'updated_by'
    ];

    /**
     * Boot function untuk menangani created_by dan updated_by secara otomatis
     */
    protected static function boot()
    {
        parent::boot();

        // Saat data akan dibuat (creating)
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        // Saat data akan diupdate (updating)
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    // Relasi untuk melihat siapa yang membuat konten ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



