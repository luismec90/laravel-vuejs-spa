<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User\User;
use App\Models\Meal\Meal;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Log;

class MealController extends APIController
{

    /**
    * @param Request $request
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function index(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'from_date' => 'date_format:Y-m-d',
                'to_date' => 'date_format:Y-m-d',
                'from_time' => 'date_format:H:i:s',
                'to_time' => 'date_format:H:i:s',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            $user = JWTAuth::parseToken()->authenticate();

            return [
                'pagination' => $user->filteredMeals()->paginate(request('pageLength')),
                'total_calories' => $user->totalFilteredCalories(),
                'today_eaten_calories' => $user->todayEatenCalories(),
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 422);
        }
    }

    /**
    * @param Request $request
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function getAll(Request $request)
    {
        try {
            return Meal::with('user')->filtered()->paginate(request('pageLength'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 422);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'calories' => 'required|integer',
                'datetime' => 'required|date|date_format:Y-m-d H:i:s'
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if ($user->isAdmin() && request()->filled('email')) {
                $validation = Validator::make($request->all(), [
                    'email' => 'required|exists:users,email',
                ]);

                if ($validation->fails()) {
                    return response()->json(['message' => $validation->messages()->first()], 422);
                }

                $user = User::where('email', request('email'))->first();
            }

            $user->meals()->create($request->all());

            return response()->json(['message' => 'Meal added!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 422);
        }
    }

    /**
    * @param Request $request
    * @param $id
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function update(Request $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $meal = Meal::where('user_id', $user->id)->find($id);

            if (!$meal) {
                return response()->json(['message' => 'Couldnot find meal!']);
            }

            $validation = Validator::make($request->all(), [
                'name'       => 'required',
                'calories' => 'required|integer',
                'datetime' => 'required|date|date_format:Y-m-d H:i:s',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            $meal = $meal->update($request->all());

            return response()->json(['message' => 'Meal updated!', 'data' => $meal]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 422);
        }
    }

    /**
    * @param Request $request
    * @param $id
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(Request $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->isAdmin()) {
                $meal = Meal::find($id);
            } else {
                $meal = Meal::where('user_id', $user->id)->find($id);
            }

            if (!$meal) {
                return response()->json(['message' => 'Couldnot find meal!'], 422);
            }

            $meal->delete();

            return response()->json(['message' => 'Meal deleted!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 422);
        }
    }
}
