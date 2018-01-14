<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User\User;
use App\Models\Meal\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Validator;
use Auth;

class UserController extends APIController
{

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        try {
            return User::filtered()->paginate(request('pageLength'));
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
    public function getAllAutocomplete(Request $request)
    {
        try {
             return User::filteredAutocomplete()->get();
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
    public function manageStore(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'calories_per_day' => 'required|integer',
                'role' => 'required|in:1,2,3',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            User::create($request->all());

            return response()->json(['message' => 'User added!']);
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
    public function manageUpdate(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'Could not find user!'], 422);
            }

            if (request()->filled('password')) {
                $input = $request->all();
            } else {
                $input = $request->except(['password', 'password_confirmation']);
            }

            if ($user->isAdmin() && request()->filled('password')) {
                return response()->json(['message' => 'You cannot change the password of an admin.'], 422);
            }

            $validation = Validator::make($input, [
                'name' => 'required',
                'calories_per_day' => 'required|integer',
                'role' => 'required|integer|in:1,2,3',
                'password' => 'sometimes|required|min:6',
                'password_confirmation' => 'sometimes|required|same:password',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            if ($user->isAdmin() && $user->role != request('role')) {
                return response()->json(['message' => 'You cannot change the role of an admin.'], 422);
            }

            if (Auth::user()->isManager() && $user->isManager() && $user->role != request('role')) {
                return response()->json(['message' => 'You cannot change the role of a manager.'], 422);
            }

            $user = $user->update($input);

            return response()->json(['message' => 'User updated!']);
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
    public function updateProfile(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Could not find user1!'], 422);
            }

            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'calories_per_day' => 'required|integer|min:0',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            $user->name = request('name');
            $user->calories_per_day = request('calories_per_day');
            $user->save();

            return response()->json(['message' => 'User updated!']);
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
    public function changePassword(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'current_password'          => 'required',
                'new_password'              => 'required|confirmed|different:current_password|min:6',
                'new_password_confirmation' => 'required|same:new_password',
            ]);

            if ($validation->fails()) {
                return response()->json(['message' => $validation->messages()->first()], 422);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!\Hash::check(request('current_password'), $user->password)) {
                return response()->json(['message' => 'Old password does not match! Please try again!'], 422);
            }

            $user->password = bcrypt(request('new_password'));
            $user->save();

            return response()->json(['message' => 'Your password has been changed successfully!']);
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
    public function manageDestroy(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'Could not find user!'], 422);
            }

            if ($user->isAdmin()) {
                return response()->json(['message' => 'Admins cannot be deleted!'], 422);
            }

            if (!Auth::user()->isAdmin() && $user->isAdminOrManager()) {
                return response()->json(['message' => 'You cannot delete admins or managers!'], 422);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted!']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return response()->json(['message' => 'Sorry, something went wrong!'], 422);
        }
    }
}
