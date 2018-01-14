<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Meal\Meal;

class MealsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $limit = rand (0, 100);
            while ($limit-- > 0) {
                $user->meals()->save(factory(Meal::class)->create([
                    'user_id' => $user->id
                ]));
            }
        }
    }
}
