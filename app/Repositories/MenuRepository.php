<?php
namespace App\Repositories;

use App\Filters\RequestOrderHandler;
use App\Filters\Types\Search;
use App\Http\Resources\Api\V1\MenuResource;
use App\Models\Meal;
use App\Repositories\Contract\FindContract;
use App\Repositories\Contract\PaginationContract;
use Illuminate\Http\Request;

class MenuRepository implements PaginationContract, FindContract
{
    
    public function paginate(Request $request)
    {
        $items = Meal::filters([
            ...app(RequestOrderHandler::class)->sort($request),
            new Search(['description', 'price', 'available_quantity'], $request->_q)
        ])->paginate($request->per_page ?? config('app.pagination', 30));

        return MenuResource::collection($items);
    }

    public function findOrFail($id){
        $reservation = Meal::usedToDay()->findOrFail($id);
        return new MenuResource($reservation, 'single');
    }
}