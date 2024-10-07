<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_total_hours',
        'monthly_total_euros',
        'month',
        'year',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }
}
