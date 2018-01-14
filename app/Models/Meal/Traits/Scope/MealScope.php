<?php

namespace App\Models\Meal\Traits\Scope;

/**
 * Trait MealScope.
 */
trait MealScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFiltered($query)
    {
        $query->whereHas('user', function ($query) {
            if (request()->has('user_email')) {
                $query->where('email', request('user_email'));
            }
            if (request()->has('user_name')) {
                $query->where('name', 'LIKE', '%' . request('user_name') . '%');
            }
        });

        return $query->orderBy('datetime', 'DESC');
    }
}
