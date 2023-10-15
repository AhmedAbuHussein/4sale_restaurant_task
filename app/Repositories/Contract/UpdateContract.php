<?php
namespace App\Repositories\Contract;

use Illuminate\Http\Request;

interface UpdateContract {

    public function update(Request $request, $id);
}