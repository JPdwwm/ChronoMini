<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    use HasFactory;


    protected $fillable = [
        'first_name',
        'birth_date'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'kid_user');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
