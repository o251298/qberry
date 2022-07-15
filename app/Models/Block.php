<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $guarded = [];
    public const VOLUME_STANDART = 2;
    public const FREE_BLOCK = 0;
    protected $hidden = ['laravel_through_key', 'created_at', 'updated_at'];

    public function getFridge()
    {
        return $this->hasOne(Fridge::class);
    }

    public function getFreeBlocks()
    {
        return $this->status === 0;
    }

    public function getBookings()
    {
        return $this->belongsToMany(Booking::class);
    }

    public function getBlockBooking($start, $end)
    {
        // занятые
        return $this->hasMany(BlockBooking::class)->where('start', '>=', $start)->where('end', '<=', $end);
    }

    public function status($start, $end)
    {
        return $this->getBlockBooking($start, $end)->get()->all() ? 1 : self::FREE_BLOCK;
    }


}
