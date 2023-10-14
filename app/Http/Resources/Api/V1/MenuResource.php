<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    private $single;
    public function __construct($resource, $single=false)
    {
        parent::__construct($resource);
        $this->single = $single;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "id"=> $this->id,
            "price"=> $this->price,
            "discount"=> $this->discount,
            "price_after_discount"=> number_format((1-($this->discount /100)) * $this->price),
            "description"=> $this->description,
            "per_day_quantity"=> $this->available_quantity,
        ];

        if($this->single){
            
            $data['used_quantity'] = $this->order_details_count;
            $data['available_quantity'] = $this->available_quantity - $this->order_details_count;
        }
        return $data;
    }
}
