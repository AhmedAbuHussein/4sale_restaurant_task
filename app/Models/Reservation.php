<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Reservation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    ############## Scope ###########
    public function scopeCheck(Builder $builder,Request $request)
    {
        return $builder->where('reservation_date', $request->reservation_date)
            ->where(function($query) use ($request){
                $query->where(function($qr) use ($request){
                    // check for start time between exist time
                    $qr->whereTime('from_time', ">=", $request->from_time)
                        ->whereTime('from_time', "<=", $request->to_time);
                })
                ->orWhere(function($qr) use ($request){
                    // check for end time between exist time
                    $qr->whereTime('to_time', ">=", $request->from_time)
                        ->whereTime('to_time', "<=", $request->to_time);
                });
            });
    }
}
