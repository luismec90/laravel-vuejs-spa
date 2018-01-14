<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Meal\Meal;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Bill',
            'email' => 'user@test.com',
            'calories_per_day' => 2000,
            'role' => 1,
            'password' => bcrypt('secret'),
        ]);

        $user = User::create([
            'name' => 'Steve',
            'email' => 'manager@test.com',
            'calories_per_day' => 2000,
            'role' => 2,
            'password' => bcrypt('secret'),
        ]);

        $user = User::create([
            'name' => 'Dennis',
            'email' => 'admin@test.com',
            'calories_per_day' => 2000,
            'role' => 3,
            'password' => bcrypt('secret'),
        ]);

        factory(User::class, 50)->create();
    }
}
