<?php
namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface CheckoutContract {

    public function checkout(Request $request);
    public function pay(Request $request);
}