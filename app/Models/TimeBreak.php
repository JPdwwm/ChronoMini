<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'break_start',
        'break_end',
        'total',
        'record_id'
    ];
}
