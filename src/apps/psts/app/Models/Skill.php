<?php

namespace App\Models;

use App\Components\Model;

class Skill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
    ];


    // each Skill BELONGS to many user
    // define our pivot table also
    public function users() {
        return $this->belongsToMany('App\Models\User', 'user_skill', 'skill_id', 'user_id');
    }
    public function getNameAttribute($value)
    {
        return str_replace('_', ' ',str_replace("US_BUF_TS_", "", $value));
    }
}
