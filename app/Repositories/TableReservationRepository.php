<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Repositories\Contract\BaseContract;

class TableReservationRepository implements BaseContract
{
    public function handler(Request $request, ...$data)
    {
        $check = 
        Table::where('id', $request->table_id)
            ->where('capacity', ">=", $request->persons)
            ->whereDoesntHave('reservations', function($query) use($request){
                $query->select('id')->check($request);
            })
            ->select('id')->count('id');

        return response()->json(['is_available'=> $check > 0], 200);
    }
}