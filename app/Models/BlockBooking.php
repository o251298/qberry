<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockBooking extends Model
{
    use HasFactory;
    protected $table = 'block_booking';
    protected $guarded = [];
}
