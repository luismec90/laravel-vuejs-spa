<?php

namespace App\Models\Meal;

use Eloquent;
use App\Models\Meal\Traits\Relationship\MealRelationship;
use App\Models\Meal\Traits\Scope\MealScope;

class Meal extends Eloquent
{
    use MealScope,
        MealRelationship;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'meals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'calories',
        'description',
        'datetime',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
