<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            "id"=> $this->id,
            "date"=> $this->reservation_date,
            "start"=> $this->from_time,
            "end"=> $this->to_time,
            "persons"=> $this->persons,
            "status"=> $this->status,
            "customer"=> [
                "name"=> optional($this->customer)->name,
                "phone"=> optional($this->customer)->phone,
            ]
        ];
    }
}
