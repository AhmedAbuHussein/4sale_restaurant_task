<?php

namespace App\Models;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory, Filters;
    protected $guarded=['id'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->firstOrFail();
    }


    ############### Scope ###########
    public function ScopeCheckAvailable($builder, $data)
    {
        $builder->when(array_key_exists('persons', $data), function($query) use ($data){
            $query->where('capacity', ">=", $data['persons']);
        })
        ->when(array_key_exists('table_id', $data), function($query) use ($data){
            $query->where('id', $data['table_id']);
        })
        ->whereDoesntHave('reservations', function($query) use ($data){
            $query->select('id')->check($data);
        });
    }
}
