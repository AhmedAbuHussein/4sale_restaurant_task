<?php
namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface CollectionContract {

    public function get(Request $request);
}