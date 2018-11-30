<?php

namespace App\Models;

use App\Components\Model;

class Topic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','cabinet_id','pos','image_url'
    ];


    // each Topic BELONGS to many content
    // define our pivot table also THIS IS DISTINCT ONLY
    public function contents() {

        return $this->belongsToMany('App\Models\Content', 'medicines', 'topic_id', 'content_id')->distinct();
    }

    // each topic has many medicines
    public function medicines() {
        return $this->hasMany('App\Models\Medicine');
    }
}
