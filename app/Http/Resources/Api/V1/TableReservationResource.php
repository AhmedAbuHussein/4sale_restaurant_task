<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TableReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->load(['reservations'=> function($query){
            $query->with(['customer'])->where('reservation_date', ">=", now())
            ->where('to_time', ">=", now());
        }]);
        return [
            "table"=> $this->id,
            "capacity"=> $this->capacity,
            "reservations"=> ReservationResource::collection($this->reservations)
        ];
    }
}
