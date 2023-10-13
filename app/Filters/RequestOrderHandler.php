<?php

namespace App\Filters;

use App\Filters\Types\Asc;
use App\Filters\Types\Desc;
use Illuminate\Http\Request;

class RequestOrderHandler
{
    protected $column;

    // public function setColumn($column)
    // {
    //     $this->column = $column;
    // }

    public function sort(Request $request)
    {
        $filters = [];

        foreach ($request->query() as $column => $value) {
            if (array_key_exists($value, $this->map($column))) {
                array_push($filters, $this->map($column)[$value]);
            }
        }

        return $filters;
    }

    public function map(string $column)
    {
        return [
            'asc' => new Asc($column),
            'desc' => new Desc($column),
        ];
    }
}