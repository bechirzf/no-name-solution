<?php

namespace App\Models;

use App\Components\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    // each department has many cabinets
    public function cabinets() {
        return $this->hasMany('App\Models\Cabinet');
    }
}
