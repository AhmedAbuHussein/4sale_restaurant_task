<?php

namespace App\Filters\Types;

use App\Filters\Contract\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class Between implements FilterContract
{
    protected $column;
    protected $min_num;
    protected $max_num;

    public function __construct(string $column, $min_num=1, $max_num=null) {
        $this->column = $column;
        $this->min_num = $min_num;
        $this->max_num = $max_num;
    }

    public function apply(Builder $query) {
        return $query->when($this->max_num && $this->min_num, function($builder){
            $builder->whereBetween($this->column ,[$this->min_num, $this->max_num]);
        })->when(is_null($this->max_num) && $this->min_num, function($builder){
            $builder->where($this->column , ">=", $this->min_num);
        });
    }
}