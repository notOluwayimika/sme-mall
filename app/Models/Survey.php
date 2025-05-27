<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
