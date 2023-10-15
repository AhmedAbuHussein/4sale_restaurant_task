<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiLoginTest extends TestCase
{

    public function test_login_success()
    {
        $response = $this->post('api/v1.0/login', [
            "email"=> "user@gmail.com",
            "password"=> "password"
        ], ["Accept"=> "application/json"]);
        
        $response->assertStatus(200)
        ->assertJsonStructure(["data"=>["token","user"]]);
    }

    public function test_login_validation()
    {
        $response = $this->post('api/v1.0/login',[
            "email"=> "user@gmail.com",
        ], ["Accept"=> "application/json"]);

        $response->assertStatus(422)
        ->assertJsonStructure(["message","errors"=> []]);
    }

    public function test_login_throttle()
    {
        for ($i=0; $i < 4; $i++) { 
            $response = $this->post('api/v1.0/login',[
                "email"=> "not-user@gmail.com",
                "password"=> "password_error",
            ], ["Accept"=> "application/json"]);
        }

        $response->assertStatus(405)
        ->assertJsonStructure(["message"]);
    }
}
