<?php

namespace App\Models;

use App\Components\Model;

class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];
    /**
     * Get the modules for the type.
     */
    public function modules()
    {
        return $this->hasMany('App\Models\Module');
    }

    // each type BELONGS to many 1on1 coaching
    // define our pivot table also
    public function mains() {
        return $this->belongsToMany('App\Models\Main', 'main_type');
    }

    // each type has many rating types
    public function ratingTypes() {
        return $this->hasMany('App\Models\RatingType');
    }
}
