<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'drop_hour',
        'pick_up_hour',
        'amount_hours',
        'date',
        'drop_status', 
        'pick_up_status' 
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
