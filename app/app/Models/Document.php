<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'name', 'description', 'file_path',
        'document_category_id', 'status',
        'is_active', 'created_by', 'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->created_by = $model->updated_by = Auth::id());
        static::updating(fn($model) => $model->updated_by = Auth::id());
    }

    // Relasi balik ke Kategori
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }
}



