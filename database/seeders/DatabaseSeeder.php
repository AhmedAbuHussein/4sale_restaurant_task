<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\User;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Support\Str;
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
        User::create([
            'name' => "test user",
            'email' => "user@gmail.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        User::factory(9)->create();
        Customer::factory(10)->create();
        Meal::factory(15)->create();
        Table::factory(10)->create();
    }
}
