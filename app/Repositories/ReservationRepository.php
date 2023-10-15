<?php
namespace App\Repositories;

use App\Http\Resources\Api\V1\ReservationResource;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use App\Repositories\Contract\FindContract;
use App\Repositories\Contract\StoreContract;
use App\Repositories\Contract\UpdateContract;
use Exception;
use Illuminate\Http\Request;

class ReservationRepository implements StoreContract, FindContract, UpdateContract
{
    public function store(Request $request)
    {
        $customer_data = $request->only(['phone', 'name']);
        $customer = Customer::updateOrCreate(['phone'=> $request->phone], $customer_data);
        $data = $request->validated();
        if($request->has('table_id') && Table::checkAvailable($data)->select('id')->count('id')){
            $reservation = $customer->reservations()->create($data);
        }elseif($request->has('waiting') && $request->waiting){
            $data['status'] = "waiting";
            $data['table_id'] = NULL;
            $reservation = $customer->reservations()->create($data);
        }else{
            throw new Exception("The table ({$data['table_id']}) not availabe at selected time", 400);
        }
        return response()->json(["data"=> new ReservationResource($reservation->fresh())], 201);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        if($request->status == "confirmed"){
            $data = [
                "table_id"=> $request->table_id,
                "reservation_date"=> $request->reservation_date ?? $reservation->reservation_date,
                "from_time"=> $reservation->from_time,
                "to_time"=> $reservation->to_time,
            ];

            if(Table::checkAvailable($data)->select('id')->count('id')){
                $reservation->update([
                    "table_id"=> $request->table_id, 
                    'status'=> 'confirmed',
                    "reservation_date"=> $request->reservation_date ?? $reservation->reservation_date,
                ]);
            }else{
                throw new Exception("The table ({$data['table_id']}) not availabe at selected time", 400);
            }
        }else{
            //cancel or waiting status
            $reservation->update(["table_id"=> null, 'status'=> $request->status]);
        }
        return new ReservationResource($reservation);
    }


    public function findOrFail($id){
        $reservation = Reservation::with('customer')->findOrFail($id);
        return new ReservationResource($reservation);
    }
}