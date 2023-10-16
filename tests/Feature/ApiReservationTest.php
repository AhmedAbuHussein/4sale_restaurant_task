<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiReservationTest extends TestCase 
{
    use WithFaker;
    public function test_reservation_store_success()
    {
        $rand = rand(0, 12);
        $from_time = now()->addHours($rand)->format('H:i:s');
        $to_time = now()->addHours($rand + 1)->format('H:i:s');
        $response = $this->post('api/v1.0/reservations', [
            "table_id"          => Table::all()->random()->id,
            "reservation_date"  => now()->addDays(rand(0, 5))->format('Y-m-d'),
            "from_time"         => $from_time,
            "to_time"           => $to_time,
            "name"              => $this->faker->name(),
            "phone"             => $this->faker->phoneNumber,
            "persons"           => rand(1, 5),
            "waiting"           => true,
        ],["Accept"=> "application/json"]);

        $response->assertStatus(201)
        ->assertJsonStructure(["data"=>[
            "id",
            "date",
            "start",
            "end",
            "persons",
            "status",
            "customer"=>['name', "phone"]
        ]]);
    }

    public function test_reservation_store_validation_error()
    {
        $rand = rand(0, 12);
        $from_time = now()->addHours($rand)->format('H:i:s');
        $to_time = now()->addHours($rand + 1)->format('H:i:s');
        $response = $this->post('api/v1.0/reservations', [
            "table_id"          => 2,
            "reservation_date"  => now()->addDays(rand(0, 5))->format('Y-m-d'),
            // "from_time"         => $from_time,
            // "to_time"           => $to_time,
            "name"              => $this->faker->name(),
            "phone"             => $this->faker->phoneNumber,
            "persons"           => rand(1, 5),
           // "waiting"           => true,
        ],["Accept"=> "application/json"]);

        $response->assertStatus(422)
        ->assertJsonStructure(["message", "errors"]);
    }

    public function test_reservation_store_with_invalid_method()
    {
        $rand = rand(0, 12);
        $from_time = now()->addHours($rand)->format('H:i:s');
        $to_time = now()->addHours($rand + 1)->format('H:i:s');
        $response = $this->put('api/v1.0/reservations', [

        ],["Accept"=> "application/json"]);

        $response->assertStatus(405)
        ->assertJsonStructure(["message"]);
    }
    public function test_reservation_store_with_existing_reservation_with_same_information()
    {
        $reservation = Reservation::orderby("id", "desc")->first();
        $response = $this->post('api/v1.0/reservations',[
            "table_id"          => $reservation->table_id,
            "reservation_date"  => $reservation->reservation_date,
            "from_time"         => $reservation->from_time,
            "to_time"           => $reservation->to_time,
            "name"              => $this->faker->name(),
            "phone"             => $this->faker->phoneNumber,
            "persons"           => rand(1, 5),
            "waiting"           => false,
        ],["Accept"=> "application/json"]);

        $response->assertStatus(400)
        ->assertJsonStructure(["message"]);
    }

    public function test_reservation_show_success()
    {
        $reservation = Reservation::first();
        $response = $this->get('api/v1.0/reservations/'.$reservation->id, [
            "Accept"=> "application/json",
        ]);

        $response->assertStatus(200);
    }

    public function test_reservation_show_model_not_found()
    {
        $response = $this->get('api/v1.0/reservations/122', [
            "Accept"=> "application/json",
        ]);

        $response->assertStatus(404)
        ->assertJsonStructure(["message"]);
    }

    public function test_reservation_update_success()
    {
        $reservation = Reservation::first();

        $response = $this->put('api/v1.0/reservations/'.$reservation->id, [
            "table_id"=> $reservation->table_id,
            "status"=> "waiting",
            "reservation_date"=> null,
        ],["Accept"=> "application/json"]);

        $response->assertStatus(200)
        ->assertJsonStructure(["data"=>[
            "id",
            "date",
            "start",
            "end",
            "persons",
            "status",
            "customer"
        ]]);
    }

    public function test_reservation_update_validation_error()
    {
        $reservation = Reservation::first();
        $response = $this->put('api/v1.0/reservations/'.$reservation->id,[
            /** validation error */
        ],["Accept"=> "application/json"]);

        $response->assertStatus(422)
        ->assertJsonStructure(["message"]);
    }

    public function test_reservation_update_model_not_found()
    {
        $response = $this->put('api/v1.0/reservations/15451',[
            "table_id"=> 1,
            "status"=> "waiting",
            "reservation_date"=> null,
        ],["Accept"=> "application/json"]);

        $response->assertStatus(404)
        ->assertJsonStructure(["message"]);
    }


}
