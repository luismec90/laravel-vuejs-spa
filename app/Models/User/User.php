<?php

namespace App\Models\User;

use App\Models\User\Traits\Relationship\UserRelationship;
use App\Models\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,
        UserScope,
        UserRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'calories_per_day',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isUser()
    {
      return $this->role == 1;
    }

    public function isManager()
    {
      return $this->role === 2;
    }

    public function isAdmin()
    {
      return $this->role === 3;
    }

    public function isAdminOrManager()
    {
      return $this->isAdmin() || $this->isManager();
    }

    public function filteredMeals()
    {
        $meals = $this->meals();

        if (request()->has('from_date')) {
            $meals->where('datetime', '>=', request('from_date') . ' 00:00:00');
        }

        if (request()->has('to_date')) {
            $meals->where('datetime', '<=', request('to_date') . ' 23:59:59');
        }

        if (request()->has('from_time')) {
            $meals->whereRaw('TIME(datetime) >= ?', [request('from_time')]);
        }

        if (request()->has('to_time')) {
            $meals->whereRaw('TIME(datetime) <= ?', [request('to_time')]);
        }


        return $meals->orderBy('datetime', 'DESC');
    }

    public function totalFilteredCalories()
    {
        return $this->filteredMeals()->sum('calories');
    }

    public function todayEatenCalories()
    {
        return $this->meals()
            ->where('datetime', '>=', date('Y-m-d 00:00:00'))
            ->where('datetime', '<=', date('Y-m-d 23:59:59'))
            ->sum('calories');
    }

}
