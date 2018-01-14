<?php

namespace App\Models\User\Traits\Scope;

/**
 * Trait UserScope.
 */
trait UserScope
{

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFiltered($query)
    {
        if (request()->has('email')) {
            $query->where('email', 'LIKE', '%' . request('email') .'%');
        }

        if (request()->has('name')) {
            $query->where('name', 'LIKE', '%' . request('name') .'%');
        }

        return $query->orderBy('name');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFilteredAutocomplete($query)
    {
        if (request()->has('q')) {
            $query->where('email', 'LIKE', '%' . request('q') . '%')
            ->orWhere('name', 'LIKE', '%' . request('q') .'%');
        }

        return $query->orderBy('name');
    }
}
