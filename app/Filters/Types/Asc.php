<?php

namespace App\Filters\Types;

use App\Filters\Contract\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class Asc implements FilterContract
{
    protected $column;

    public function __construct(string $column) {
        $this->column = $column;
    }

    public function apply(Builder $query) {
        return $query->when(!is_null($this->column) && Schema::hasColumn($query->getModel()->getTable(), $this->column), function($builder){
            return $builder->orderBy($this->column, 'asc');
        });
    }
}