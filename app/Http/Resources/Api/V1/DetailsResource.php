<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "id"        => $this->id,
            "meal_id"   => $this->meal_id,
            "amount"    => $this->amount_to_pay,
        ];
        if($request->has('checkout')){
            $this->load("meal");
            $price = round(((1- $this->meal->discount/100) * $this->meal->price)  , 2);
            $data['price'] = $this->meal->price;
            $data['discount'] = $this->meal->discount ."%";
            $data['total'] = round($price * $this->amount_to_pay, 2);
        }
        return $data;
    }
}
