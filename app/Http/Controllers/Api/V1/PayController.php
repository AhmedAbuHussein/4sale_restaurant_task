<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PayRequest;
use App\Repositories\PayRepository;

class PayController extends Controller
{

    private $repository;
    
    public function __construct(PayRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkout(PayRequest $request)
    {
        return $this->repository->checkout($request);
    }

    public function pay(PayRequest $request)
    {
        return $this->repository->pay($request);
    }
}
