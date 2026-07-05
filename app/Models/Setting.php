<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'work_start',
        'late_tolerance',
        'work_end',
        'latitude',
        'longitude',
        'radius',
    ];
}
