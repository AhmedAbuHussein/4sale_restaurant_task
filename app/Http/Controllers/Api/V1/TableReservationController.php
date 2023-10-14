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
     * Display the specified table.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function check_available(TableReservationRequest $request)
    {
        return $this->repository->handler($request);
    }
}
