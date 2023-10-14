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
    public function scopeCheck(Builder $builder, $data)
    {
        $check = array_key_exists('from_time', $data) && array_key_exists('to_time', $data);
        return $builder
            ->when(array_key_exists('reservation_date', $data), function($query) use ($data){
                $query->where('reservation_date', $data['reservation_date']);
            })
            ->when($check, function($query) use ($data){
                $query->where(function($query) use ($data){
                    # inner `where` to prevent overwrite any where else
                    $query->where(function($qr) use ($data){
                        // check for start time between exist time
                        $qr->whereTime('from_time', ">=", $data['from_time'])
                            ->whereTime('from_time', "<=", $data['to_time']);
                    })
                    ->orWhere(function($qr) use ($data){
                        // check for end time between exist time
                        $qr->whereTime('to_time', ">=", $data['from_time'])
                            ->whereTime('to_time', "<=", $data['to_time']);
                    });
                });
            });
    }
}
