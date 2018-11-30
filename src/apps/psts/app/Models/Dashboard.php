<?php

namespace App\Models;

use App\Components\Model;

class Dashboard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'created_by',
        'updated_by',
    ];

    // each dashboard BELONGS to many 1 on 1 coaching
    // define our pivot table also
    public function mains() {
        return $this->belongsToMany('App\Models\Main', 'main_dashboard');
    }
}
