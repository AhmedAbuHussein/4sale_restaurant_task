<?php

use Illuminate\Support\Facades\DB;

if(!function_exists("calculate_order_total")){
    function calculate_order_total(int $order_id){
        $calculate_total = DB::table('order_details')
            ->where('order_details.order_id', $order_id)
            ->join('meals', 'order_details.meal_id', '=', 'meals.id')
            ->selectRaw("SUM((1-(meals.discount/100)) * meals.price * order_details.amount_to_pay) as total")
            ->first("total");
        return $calculate_total->total ?? 0;
    }
}

if(!function_exists("check_meal_availabilty")){
    function check_meal_availabilty(int $meal_id){
        $calculate_total = DB::table('meals')
            ->where('meals.id', $meal_id)
            ->join('order_details', 'order_details.meal_id', '=', 'meals.id')
            ->selectRaw("meals.available_quantity - SUM(order_details.amount_to_pay) as available")
            ->groupBy('meals.available_quantity')
            ->first("available");
        return $calculate_total->available ?? 0;
    }
}