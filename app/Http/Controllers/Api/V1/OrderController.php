<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OrderRequest;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $repository;
    
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Display the specified id.
     *
     * @param  \App\Models\Meal  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        return $this->repository->store($request);
    }
}
