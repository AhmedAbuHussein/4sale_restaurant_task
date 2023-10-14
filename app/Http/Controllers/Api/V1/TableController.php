<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TableReservationResource;
use App\Http\Resources\Api\V1\TableResource;
use App\Repositories\TableRepository;
use Illuminate\Http\Request;

class TableController extends Controller
{
    private $repository;
    
    public function __construct(TableRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the tables.
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        return TableResource::collection($this->repository->paginate($request));   
    }

    /**
     * Display the specified table.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {
        return new TableReservationResource($this->repository->findOrFail($table));
    }
}
