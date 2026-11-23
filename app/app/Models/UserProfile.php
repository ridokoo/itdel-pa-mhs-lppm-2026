<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    protected $primaryKey = 'user_id'; // Kunci utama merujuk ke tabel users
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'study_program_id',
        'functional_position_id',
        'nidn',
        'phone_number',
        'public_email',
        'gender',
        'profile_photo',
        'created_by',
        'updated_by'
    ];

    // Relasi ke User Utama
    public function user() { return $this->belongsTo(User::class, 'user_id'); }

    // Relasi ke Master Data yang sudah kita buat sebelumnya
    public function studyProgram() { return $this->belongsTo(StudyProgram::class, 'study_program_id'); }
    public function functionalPosition() { return $this->belongsTo(FunctionalPosition::class, 'functional_position_id'); }
}



