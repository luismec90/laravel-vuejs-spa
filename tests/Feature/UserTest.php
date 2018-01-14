<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User\User;
use App\Models\Meal\Meal;
use JWTAuth;

class UserTest extends TestCase
{
    public function testsUsersAreListedCorrectly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $user->role = 2;
        $user->save();

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];

        $this->json('GET', '/api/v1/manage/user', [], $headers)
            ->assertStatus(200);
    }

    public function testsUsersAreCreatedCorrectly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $user->role = 3;
        $user->save();

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'name' => 'Luis Montoya',
            'email' => 'luismec90@gmail.com',
            'calories_per_day' => '20',
            'role' => '1',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $this->json('POST', '/api/v1/manage/user', $payload, $headers)
            ->assertStatus(200)
            ->assertJson(['message' => 'User added!']);
    }

    public function testsUsersAreEditedCorrectly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $user->role = 3;
        $user->save();

        $user = User::create([
            'name' => 'Luis Montoya',
            'email' => 'luismec90@gmail.com',
            'calories_per_day' => '20',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'name' => 'Luis Fernando Montoya',
            'calories_per_day' => '22',
            'role' => '2',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $this->json('PATCH', '/api/v1/manage/user/' . $user->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJson(['message' => 'User updated!']);
    }

    public function testsUsersAreDeletedCorrectly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $user->role = 3;
        $user->save();

        $user = User::create([
            'name' => 'Luis Montoya',
            'email' => 'luismec90@gmail.com',
            'calories_per_day' => '20',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];

        $this->json('DELETE', '/api/v1/manage/user/' . $user->id, [], $headers)
            ->assertStatus(200)
            ->assertJson(['message' => 'User deleted!']);
    }
}
