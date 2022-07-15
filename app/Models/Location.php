<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getFridges()
    {
        return $this->hasMany(Fridge::class);
    }

    public function getBlocks()
    {
        return $this->hasManyThrough(Block::class, Fridge::class);
    }

    public function getFreeBlocks()
    {
        return $this->hasManyThrough(Block::class, Fridge::class)->where('status', 0);
    }
}
