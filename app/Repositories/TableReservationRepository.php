<?php
namespace App\Repositories;

use App\Http\Resources\Api\V1\TableResource;
use App\Models\Table;
use App\Repositories\Contract\BaseContract;

class TableReservationRepository implements BaseContract
{
    public function handler($data)
    {
        $check = Table::checkAvailable($data)->newQuery();
        
        if(!array_key_exists('table_id', $data)){
            $per_page = array_key_exists('per_page', $data) ? $data['per_page'] : config('app.pagination', 30);
            $check = $check->paginate($per_page);
            return TableResource::collection($check);
        }else{
            $check = $check->select("id")->count('id');
            return response()->json(['is_available'=> $check > 0], 200);
        }
    }
}