<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User\User;
use App\Models\Meal\Meal;
use JWTAuth;

class MealTest extends TestCase
{
    public function testsMealsAreListedCorrectly()
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

        $this->json('GET', '/api/v1/meal', [], $headers)
            ->assertStatus(200);
    }

    public function testsMealsAreCreatedCorrectly()
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
        $payload = [
            'name' => 'test',
            'calories' => '20',
            'datetime' => '2017-12-23 01:47:47',
        ];

        $this->json('POST', '/api/v1/meal', $payload, $headers)
            ->assertStatus(200)
            ->assertJson(['message' => 'Meal added!']);
    }

    public function testsMealsAreEditedCorrectly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $meal = $user->meals()->create([
            'name' => 'Fish',
            'calories' => '20',
            'datetime' => '2017-12-23 02:12:10',
        ]);

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'name' => 'Bread',
            'calories' => '30',
            'datetime' => '2017-12-21 01:47:47',
        ];

        $this->json('PATCH', '/api/v1/meal/' . $meal->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJson(['message' => 'Meal updated!']);
    }

    public function testsMealsAreDeletedCorrectly()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'calories_per_day' => '20',
            'password' => bcrypt('secret')
        ]);

        $meal = $user->meals()->create([
            'name' => 'Fish',
            'calories' => '20',
            'datetime' => '2017-12-23 02:12:10',
        ]);

        $token = JWTAuth::attempt([
            'email' => 'john@test.com',
            'password' => 'secret',
        ]);

        $headers = ['Authorization' => "Bearer $token"];

        $this->json('DELETE', '/api/v1/meal/' . $meal->id, [], $headers)
            ->assertStatus(200)
            ->assertJson(['message' => 'Meal deleted!']);
    }
}
