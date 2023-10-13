<?php
namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;

trait Filters
{
    public function scopeFilters(Builder $builder, array $filters)
    {
        foreach ($filters as $filter) {
            $filter->apply($builder);
        }
    }
}