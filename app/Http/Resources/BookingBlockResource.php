<?php

namespace App\Http\Resources;

use App\Services\Timezone\TimezoneCreator;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'block_id' => $this->block_id,
            'start' => TimezoneCreator::createUserDate($this->getBlock()->first()->getLocation()->first()['timezone'], $this->start),
            'end' => TimezoneCreator::createUserDate($this->getBlock()->first()->getLocation()->first()['timezone'], $this->end),
        ];
    }
}
