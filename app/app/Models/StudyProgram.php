<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudyProgram extends Model
{
    protected $table = 'study_programs';

    // Konfigurasi Primary Key String
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'faculty_id',
        'name',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // Logika Auto-Generate ID 10 Karakter
    protected static function booted()
    {
        static::creating(function ($prodi) {
            if (empty($prodi->id)) {
                $prodi->id = strtoupper(Str::random(10));
            }
        });
    }

    // RELASI: Program Studi dimiliki oleh satu Fakultas
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    // RELASI: Siapa yang membuat data ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



