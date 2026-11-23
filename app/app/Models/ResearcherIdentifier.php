<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearcherIdentifier extends Model
{
    protected $table = 'researcher_identifiers';

    protected $fillable = [
        'user_id', 'database_index_id', 'author_id', 'profile_url',
        'created_by', 'updated_by', 'is_active'
    ];

    public function index()
    {
        return $this->belongsTo(DatabaseIndex::class, 'database_index_id');
    }
}



