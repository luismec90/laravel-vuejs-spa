<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    public function testsRegistersSuccessfully()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $this->json('post', '/api/v1/auth/register', $payload)
            ->assertStatus(200);

    }
}
