<?php

namespace App\Models;

use App\Components\Model;

class Schedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'time_in',
        'time_out'
    ];
    public function getTimeInAttribute() {
        return date('H:i', strtotime($this->attributes['time_in']));
    } 
    public function getTimeOutAttribute() {
        return date('H:i', strtotime($this->attributes['time_out']));
    } 
    // each Skill BELONGS to many user
    // define our pivot table also
    public function users() {
        return $this->belongsToMany('App\Models\User', 'user_schedule', 'schedule_id', 'user_id');
    }
}
