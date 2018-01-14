<?php

use Faker\Generator as Faker;
use App\Models\User\User;
use App\Models\Meal\Meal;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(Meal::class, function ($faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'name' => $faker->word,
        'calories' => rand(0, 10000),
        'datetime' =>  $faker->dateTime,
    ];
});
