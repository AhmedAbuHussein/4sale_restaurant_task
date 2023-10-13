<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Meal;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Customer::factory(10)->create();
        Meal::factory(15)->create();
        Table::factory(10)->create();
    }
}
