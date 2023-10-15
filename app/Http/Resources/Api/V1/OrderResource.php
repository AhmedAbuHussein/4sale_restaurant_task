<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "total"=> $this->total,
            "paid"=> $this->paid == 1 ? 'Yes' : 'No',
            "date"=> $this->date,
            "customer"=> [
                "name"=> optional($this->customer)->name,
                "phone"=> optional($this->customer)->phone,
            ],
            "details"=> DetailsResource::collection($this->order_details)
        ];
    }
}
