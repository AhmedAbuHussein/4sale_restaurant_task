<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiLoginTest extends TestCase
{

    public function test_login_success()
    {
       // $this->withoutExceptionHandling();
        $response = $this->post('api/v1.0/login', [
            "email"=> "user@gmail.com",
            "password"=> "password"
        ], ["Accept"=> "application/json"]);
        
        $response->assertStatus(200)
        ->assertJsonStructure(["data"=>["token","user"]]);
    }

    public function test_login_fail()
    {
        $response = $this->post('api/v1.0/login',[
            "email"=> "userssdfasd@gmail.com",
            "password"=> "passwsdfasdford"
        ], ["Accept"=> "application/json"]);

        $response->assertStatus(422)
        ->assertJsonStructure(["message","errors"=> ["email"]]);
    }
}
