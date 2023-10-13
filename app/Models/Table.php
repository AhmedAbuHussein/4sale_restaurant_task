<?php

namespace App\Models;

use App\Filters\Filters;
use Illuminate\Support\Str;
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
        $id = Str::after($value, "TB-");
        return $this->where('id', $id)->firstOrFail();
    }
}
