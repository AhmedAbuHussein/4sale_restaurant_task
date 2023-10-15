<?php
namespace App\Repositories;

use App\Http\Resources\Api\V1\InvoiceResource;
use App\Models\Order;
use App\Repositories\Contract\CheckoutContract;
use Illuminate\Http\Request;
use Exception;

class PayRepository implements CheckoutContract
{
    public function checkout(Request $request)
    {
        try {
            $order = Order::where("reservation_id", $request->reservation_id)->with(['order_details.meal'])->first();
            if($order->paid){
                return response()->json(["message"=> "The order is alreay paid"], 200);
            }
            $order->update(['total'=> calculate_order_total($order->id)]);
            return new InvoiceResource($order);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() ?? 400);
        }
    }

    public function pay(Request $request)
    {
        try {
            $order = Order::where("reservation_id", $request->reservation_id)->with(['order_details.meal'])->first();
            if($order->paid){
                return response()->json(["message"=> "The order is alreay paid"], 200);
            }
            $order->update(['total'=> calculate_order_total($order->id)]);
            ##############################################
            ########## execute payment proceess ##########
            ##############################################

            $order->update(['paid'=> 1]);
            return new InvoiceResource($order);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode() ?? 400);
        }
    }
  
}