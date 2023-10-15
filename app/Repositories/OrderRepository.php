<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\Reservation;
use App\Repositories\Contract\FindContract;
use App\Repositories\Contract\StoreContract;
use App\Http\Resources\Api\V1\OrderResource;
use App\Repositories\Contract\UpdateContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class OrderRepository implements StoreContract, UpdateContract, FindContract
{

    public function findOrFail($id){
        $order = Order::with(['order_details', 'customer'])->findOrFail($id);
        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        try {
            $data = $this->getOrderData($request);
            $order = Order::updateOrCreate(['reservation_id'=> $data['reservation_id']], $data);
            return $this->handle_order_details($request, $order);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            return $this->handle_order_details($request, $order, 200);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 400);
        }
    }
    

    ############################### Helpers ############################
    
    private function getOrderData(Request $request) :array
    {
        $reservation = Reservation::findOrFail($request->reservation_id);
        $data = [
            "reservation_id" => $reservation->id,
            "customer_id"=> $reservation->customer_id,
            "table_id"=> $reservation->table_id,
            "user_id"=> $request->user()->id,
            "date"=> now()->format('Y-m-d'),
        ];
        return $data;
    }


    private function increment_if_exists(Order $order, $meal){
        $order_detail = $order->order_details()
            ->where(["meal_id"=> $meal['id']])
            ->first();
        if($order_detail){
            $order_detail->increment("amount_to_pay", $meal['amount']);
        }else{               
            $this->sync_order_details($order, $meal);
        }
    }

    private function sync_order_details(Order $order, $meal){
        return $order->order_details()
        ->updateOrCreate([
            "meal_id"=> $meal['id']
        ],[
            "amount_to_pay"=> $meal['amount'],
        ]);
    }


    private function calculate_order_total(int $order_id){
        $calculate_total = DB::table('order_details')
            ->where('order_details.order_id', $order_id)
            ->join('meals', 'order_details.meal_id', '=', 'meals.id')
            ->selectRaw("SUM((1-(meals.discount/100)) * meals.price * order_details.amount_to_pay) as total")
            ->first("total");
        return $calculate_total->total ?? 0;
    }

    private function check_meal_availabilty(int $meal_id){
        $calculate_total = DB::table('meals')
            ->where('meals.id', $meal_id)
            ->join('order_details', 'order_details.meal_id', '=', 'meals.id')
            ->selectRaw("meals.available_quantity - SUM(order_details.amount_to_pay) as available")
            ->groupBy('meals.available_quantity')
            ->first("available");
        return $calculate_total->available ?? 0;
    }

    public function handle_order_details(Request $request, Order $order, $status = 201)
    {
        $fails = [];
        $message = "";
        foreach ($request->meals as $meal){
            if($this->check_meal_availabilty($meal['id']) >= $meal['amount']) {
                if($request->type == "increment") $this->increment_if_exists($order, $meal);
                else $this->sync_order_details($order, $meal);
            }else{
                $fails[] = $meal;
                $message = "The Required Amount of meal is not currently available!";
            }
        }
        $total = $this->calculate_order_total($order->id);
        $order->update(["total" => $total]);
        $order->load(['order_details', 'customer']);
        return response()->json(["data"=> new OrderResource($order), "fails"=> $fails, "message"=> $message], $status);
    }
}