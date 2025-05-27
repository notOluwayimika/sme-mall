<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
