<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BlockBooking extends Model
{
    use HasFactory;

    protected $table = 'block_booking';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function getBlock()
    {
        return $this->hasOne(Block::class, 'id', 'block_id');
    }

    public static function updateStatus(): void
    {
        $carbon = Carbon::now();
        // status active (reserved)
        $blockBookingActive = BlockBooking::query()
            ->where('start', '<=', $carbon->toDateString())
            ->where('end', '>=', $carbon->toDateString())->get();

        // status passive (not reserved)
        $blockBookingPassive = BlockBooking::query()
            ->where('start', '>', $carbon->toDateString())
            ->where('end', '>', $carbon->toDateString())->get();

        foreach ($blockBookingPassive as $passive) {
            // set status not reserved
            $obj         = $passive->getBlock()->first();
            $obj->status = Block::FREE_BLOCK;
            $obj->save();
        }
        foreach ($blockBookingActive as $active) {
            // set status reserved
            $obj         = $active->getBlock()->first();
            $obj->status = 1;
            $obj->save();
        }
    }
}
