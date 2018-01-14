<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User\User;
use JWTAuth;

class LogoutTest extends TestCase
{
    public function testUserIsLoggedOutProperly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];

        $this->json('GET', '/api/v1/meal', [], $headers)->assertStatus(200);
        $this->json('POST', '/api/v1/auth/logout', [], $headers)->assertStatus(200);
    }
}
