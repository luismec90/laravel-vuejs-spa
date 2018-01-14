<?php

namespace Tests\Feature\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User\User;

class LoginTest extends TestCase
{

    public function testUserLoginsSuccessfully()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $payload = ['email' => 'john@test.com', 'password' => 'secret'];

        $this->json('POST', 'api/v1/auth/login', $payload)
            ->assertStatus(200);

    }
}
