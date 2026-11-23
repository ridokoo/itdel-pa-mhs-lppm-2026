<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HakAksesModel extends Model
{
    protected $table = 'm_hak_akses';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'akses',
    ];

    public $timestamps = true;
}



