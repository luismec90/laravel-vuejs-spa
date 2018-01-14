<?php

namespace App\Models\User\Traits\Relationship;

use App\Models\Meal\Meal;

/**
 * Trait UserRelationship.
 */
trait UserRelationship
{

    /**
    * One-to-Many relationship with meals.
    *
    * @return mixed
    */
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
