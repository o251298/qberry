<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Block extends Model
{
    use HasFactory;

    protected $guarded = [];
    public const VOLUME_STANDART = 2;
    public const FREE_BLOCK = 0;
    public const PAYMENT_PER_DAY = 10;
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

    public function getLocation()
    {
        return $this->hasOneThrough(Location::class, Fridge::class, 'id', 'id', 'fridge_id', 'location_id');

    }

    public static function getBlockTest($start, $end, $fridge_id)
    {
        $activeBlocks = self::sqlGetBlocks($start, $end);
        $ids          = self::getActiveId($activeBlocks);
        return self::query()->whereIn('fridge_id', $fridge_id)->whereNotIn('id', $ids)->orderBy('fridge_id', 'DESC');
    }


    public static function sqlGetBlocks($start, $end)
    {
        $sql       = "SELECT * FROM block_booking
WHERE ('$start' BETWEEN `start` AND `end`) OR ('$end' BETWEEN `start` AND `end`) OR (`start` > '$start' AND `end` < '$end')";
        $statement = DB::select($sql);
        return $statement;
    }

    public static function getActiveId(array $statement)
    {
        $ids = [];
        foreach ($statement as $item) {
            $ids[] = $item->block_id;
        }
        return $ids;
    }
}
