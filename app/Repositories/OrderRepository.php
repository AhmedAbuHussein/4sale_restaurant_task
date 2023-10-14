<?php
namespace App\Repositories;

use App\Http\Resources\Api\V1\ReservationResource;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use App\Repositories\Contract\FindContract;
use App\Repositories\Contract\StoreContract;
use Exception;
use Illuminate\Http\Request;

class OrderRepository implements StoreContract, FindContract
{
    public function store(Request $request)
    {
        $customer_data = $request->only(['phone', 'name']);
        $customer = Customer::updateOrCreate(['phone'=> $request->phone], $customer_data);
        $data = $request->validated();
        if(Table::checkAvailable($data)->select('id')->count('id')){
            $reservation = $customer->reservations()->create($data);
            return response()->json(["data"=> new ReservationResource($reservation)], 201);
        }else{
            throw new Exception("The table ({$data['table_id']}) not availabe at selected time", 400);
        }
    }

    public function findOrFail($id){
        $reservation = Reservation::with('customer')->findOrFail($id);
        return response()->json(["data"=> new ReservationResource($reservation)], 200);
    }
}