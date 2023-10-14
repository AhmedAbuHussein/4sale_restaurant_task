<?php
namespace App\Repositories;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Filters\Types\Search;
use App\Filters\RequestOrderHandler;
use App\Repositories\Contract\FindContract;
use App\Repositories\Contract\PaginationContract;

class TableRepository implements FindContract, PaginationContract {
    
    public function findOrFail($id)
    {
        return Table::findOrfail($id);
    }

    public function paginate(Request $request){
        return Table::filters([
            ...app(RequestOrderHandler::class)->sort($request),
            new Search('capacity', $request->_q)
        ])->paginate($request->per_page ?? config('app.pagination', 30));
    }
}