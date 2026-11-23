<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';
    protected $fillable = ['name', 'is_active', 'created_by', 'updated_by'];

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

    // Relasi ke Dokumen (Satu kategori punya banyak dokumen)
    public function documents()
    {
        return $this->hasMany(Document::class, 'document_category_id');
    }
}



