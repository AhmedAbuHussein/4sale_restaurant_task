<?php

namespace App\Filters\Types;

use Illuminate\Support\Str;
use App\Filters\Contract\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class Search implements FilterContract
{
    protected $columns;
    protected $search;
    protected $operator;

    public function __construct($columns, string $search=null, string $operator="LIKE"){
        $this->columns = $columns;
        $this->search = $search;
        $this->operator = $operator;
    }

    public function apply(Builder $query) {
        if($this->search){
            if(is_array($this->columns)){
                return $query->where(function($builder){
                    foreach ($this->columns as $index=>$column) {
                        if (Str::contains($column, '.')) {
                            $relation = Str::before($column, '.');
                            $field = Str::after($column, '.');
                            if ($index == 0) {
                                $builder->whereHas($relation, function ($qry) use ($field) {
                                    $qry->where("$field", $this->operator, "%{$this->search}%");
                                });
                            } else {
                                $builder->orWhereHas($relation, function ($qry) use ($field) {
                                    $qry->where("$field", $this->operator, "%{$this->search}%");
                                });
                            }
                        }else{
                            if ($index == 0) {
                                $builder->where($column, $this->operator, "%{$this->search}%");
                            } else {
                                $builder->orWhere($column, $this->operator, "%{$this->search}%");
                            } 
                        }
                    }
                });
            }elseif(is_string($this->columns)){
                return $query->where($this->columns, $this->operator, "%{$this->search}%");
            }
        }
        return $query; 
    }
}