<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Faculty extends Model
{
    protected $table = 'faculties';

    // Konfigurasi Primary Key String
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // Logika Auto-Generate ID 10 Karakter
    protected static function booted()
    {
        static::creating(function ($faculty) {
            if (empty($faculty->id)) {
                $faculty->id = strtoupper(Str::random(10));
            }
        });
    }

    // RELASI: Satu Fakultas memiliki banyak Program Studi
    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class, 'faculty_id', 'id');
    }

    // RELASI: Siapa yang membuat data ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



