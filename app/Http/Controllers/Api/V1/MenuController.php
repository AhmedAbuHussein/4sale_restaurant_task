<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private $repository;
    
    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the meal.
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        return $this->repository->paginate($request);   
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
}
