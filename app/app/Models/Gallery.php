<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class Gallery extends Model
{
    protected $table = 'galleries';

    // created_at dan updated_at tidak perlu masuk sini
    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'event_date',
        'status',
        'is_active',
        'created_by', // Pastikan ini ada di fillable
        'updated_by'  // Pastikan ini ada di fillable
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

    // Relasi untuk melihat siapa yang membuat album ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke Gallery Items (Satu album punya banyak item)
    public function items()
    {
        return $this->hasMany(GalleryItem::class, 'gallery_id');
    }
}



