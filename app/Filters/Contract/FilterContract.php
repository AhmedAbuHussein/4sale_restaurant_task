<?php
namespace App\Filters\Contract;


use Illuminate\Database\Eloquent\Builder;

interface FilterContract
{
    public function apply(Builder $query);
}