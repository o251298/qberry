<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getBlockFromBookings() : HasMany
    {
        return $this->hasMany(BlockBooking::class);
    }

    public static function createInvoice(User $user): Builder
    {
        $carbon = Carbon::now();
        $m      = $carbon->month;
        $y      = $carbon->year;
        $payments = Booking::query()->where('user_id', $user->id)->whereBetween('date_payment', ["{$y}-{$m}-01", "{$y}-{$m}-31"]);
        return $payments;
    }
}
