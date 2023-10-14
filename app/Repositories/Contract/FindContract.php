<?php
namespace App\Repositories\Contract;

interface FindContract {

    public function findOrFail($id);

}