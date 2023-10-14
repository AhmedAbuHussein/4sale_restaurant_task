<?php

namespace App\Models;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory, Filters;
    protected $guarded = ['id'];
    
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'meal_id');
    }

    ############ Scope###########
    public function ScopeUsedToDay($query)
    {
        return $query->withCount(['order_details'=> function($builder){
            $day = now()->format('Y-m-d');
            $builder->whereBetween('order_details.created_at', [$day." 00:00:00", $day." 23:59:59"]);
        }]);
    }
}
