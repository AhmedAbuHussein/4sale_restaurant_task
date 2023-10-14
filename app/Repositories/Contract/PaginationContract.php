<?php
namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface PaginationContract {

    public function paginate(Request $request);
}