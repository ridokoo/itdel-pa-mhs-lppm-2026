<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class GalleryItem extends Model
{
    protected $table = 'gallery_items';

    protected $fillable = [
        'gallery_id',
        'file_path',
        'caption',
        'media_type',
        'display_order',
        'is_active',
        'created_by', // Pastikan ini ada di fillable
        'updated_by'  // Pastikan ini ada di fillable
    ];

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

    // Relasi ke Gallery Album (Satu item milik satu album)
    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    // Relasi untuk melihat siapa yang mengupload item ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



