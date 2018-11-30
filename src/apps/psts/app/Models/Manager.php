<?php

namespace App\Models;

use App\Components\Model;

class Manager extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'position_id', 
    ];

    // each manager has many call audits
    public function callAudits() {
        return $this->hasMany('App\Models\Monitoring');
    }

    // each manager has many agents
    public function agents() {
        return $this->hasMany('App\Models\User');
    }

    // each manager has one position
    public function position() {
        return $this->hasOne('App\Models\Position','id','position_id');
    }
}
