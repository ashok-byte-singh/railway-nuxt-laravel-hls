<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    //
    protected $fillable = [
        'title',
        'aim',
        'objective',
        'theory',
        'procedure',
        'video_url',
        'is_active',
    ];
}
