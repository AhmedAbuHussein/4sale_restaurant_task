<?php
namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface BaseContract {

    public function handler(Request $request, ...$data);
}