<?php

namespace App\Models\Meal\Traits\Relationship;

use App\Models\User\User;

/**
 * Trait MealRelationship.
 */
trait MealRelationship
{

    /**
     * One-to-one relationship with user.
     *
     * @return mixed
     */
     public function user()
     {
         return $this->belongsTo(User::class);
     }
}
