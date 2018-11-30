<?php

namespace App\Models;

use App\Components\Model;

class Monitoring extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'manager_id',
        'call_date',
        'call_url',
        'quote_id',
        'notes',
        'created_by',
        'updated_by'
    ];
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // each call audit BELONGs to many users 
    public function users() {
        return $this->belongsTo('App\Models\User','user_id');
    }
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // each call audit BELONGs to many managers 
    public function managers() {
        return $this->belongsTo('App\Models\Manager','manager_id');
    }
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // each call audit BELONGs to many managers who audit the calls
    public function auditors() {
        return $this->belongsTo('App\Models\Manager','created_by','email');
    }
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function rating_type(){
        return $this->belongsToMany('App\Models\RatingType', 'monitoring_rating_type','monitoring_id','rating_type_id')
                        ->withPivot(['notes']);
    }
    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function ratings() {
        return $this->belongsToMany('App\Models\Rating', 'monitoring_rating_type','monitoring_id','rating_id')->withPivot(['rating_type_id']);
    }
}
