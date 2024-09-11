<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
