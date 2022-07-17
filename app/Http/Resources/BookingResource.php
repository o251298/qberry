<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'user_id'              => $this->user_id,
            'hash'                 => $this->hash,
            'password_for_booking' => $this->password_for_booking,
            'amount'               => $this->amount,
            'blocks'               => [
                BookingBlockResource::collection($this->getBlockFromBookings()->get())
            ]
        ];
    }
}
