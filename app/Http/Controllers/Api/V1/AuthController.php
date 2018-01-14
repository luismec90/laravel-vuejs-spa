<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;


class AuthController extends APIController
{

    /**
    * @param Request $request
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'calories_per_day' => 'required|integer',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password',
            ]);

            if (request('calories_per_day') < 1) {
                return response()->json(['message' => 'The calories per day field must be greater than or equal to 1.'], 422);
            }

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            $user = User::create([
                'name' => request('name'),
                'email' => request('email'),
                'calories_per_day' => request('calories_per_day'),
                'role' => 1,
                'password' => bcrypt(request('password'))
            ]);

            return response()->json(['message' => 'You have registered successfully!']);
        } catch (JWTException $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 500);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials! Please try again.'], 422);
            }

            return response()->json(['message' => 'You are successfully logged in!', 'token' => $token]);
        } catch (JWTException $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser()
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['authenticated' => false], 422);
        }
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user->today_eaten_calories =  $user->todayEatenCalories();

            return response()->json(compact('user'));
        } catch (JWTException $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response(['authenticated' => false]);
        }

        return response(['authenticated' => true]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            if ($token) {
                JWTAuth::invalidate($token);
            }
        } catch (JWTException $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json(['message' => 'You are successfully logged out!']);
    }
}
