<?php

namespace App\Models;

use App\Components\Model;

class Main extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'manager_id',
        'coached_date',
        'notes',
        'created_by',
        'updated_by'
    ];

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // each 1on1 BELONGs to many users 
    public function users() {
        return $this->belongsTo('App\Models\User','user_id');
    }

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function dashboards() {
        return $this->belongsToMany('App\Models\Dashboard', 'main_dashboard');
    }

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function types() {
        return $this->belongsToMany('App\Models\Type', 'main_type')->withPivot('notes');
    }
}
