<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TableReservationRequest;
use App\Repositories\TableReservationRepository;

class TableReservationController extends Controller
{
    private $repository;
    
    public function __construct(TableReservationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function index(TableReservationRequest $request)
    {
        $data = $request->validated();
        return $this->repository->handler($data);
    }

    /**
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(TableReservationRequest $request, $table)
    {
        $data = $request->validated();
        $data['table_id'] = $table;
        return $this->repository->handler($data);
    }
}
