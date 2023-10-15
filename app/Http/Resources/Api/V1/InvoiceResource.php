<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            "id"=> $this->id,
            "paid"=> $this->paid == 1 ? 'Yes' : 'No',
            "date"=> $this->date,
            "customer"=> [
                "name"=> optional($this->customer)->name,
                "phone"=> optional($this->customer)->phone,
            ],
            "details"=> DetailsResource::collection($this->order_details)
        ];
        if($request->checkout == "service_only"){
            $service = $request->service ?? config('checkout.service.single', 15);
            $data["checkout"]['sub_total'] = $this->total;
            $data["checkout"]["service"] =  $service. "%";
            $data["checkout"]['total'] = round($this->total + (($service / 100) * $this->total), 2);
        }elseif($request->checkout == "service_tax"){
            $service = $request->service ?? config('checkout.service.with_tax', 20);
            $tax = $request->tax ?? config('checkout.tax', 14);
            $data["checkout"]['sub_total'] = $this->total;
            $data["checkout"]["service"] = $service . "%";
            $data["checkout"]["tax"] = $tax . "%";
            $data["checkout"]['total'] = round($this->total + (($tax / 100) * $this->total) + (($service / 100) * $this->total), 2);
        }
        return $data;
    }
}
