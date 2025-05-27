<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
