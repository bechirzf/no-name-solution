<?php

namespace App\Models;

use App\Components\Model;

class Cabinet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','pos'
    ];

    // each cabinet has many topics
    public function topics() {
        return $this->hasMany('App\Models\Topic');
    }

    // each Cabinet BELONGS to a department
    public function department() {

        return $this->belongsTo('App\Models\Department');
    }

    
}
