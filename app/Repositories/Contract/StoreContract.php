<?php
namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface StoreContract {

    public function store(Request $request);
}