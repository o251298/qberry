<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fridge extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getLocation()
    {
        return $this->hasOne(Location::class);
    }

    public function getBlocks()
    {
        return $this->hasMany(Block::class);
    }
}
