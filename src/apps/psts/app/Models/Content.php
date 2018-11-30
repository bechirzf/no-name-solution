<?php

namespace App\Models;

use App\Components\Model;

class Content extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','pos','image_url'
    ];

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function topics() {

        return $this->belongsToMany('App\Models\Topic', 'medicines');
    }
}
