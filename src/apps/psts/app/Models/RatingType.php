<?php

namespace App\Models;

use App\Components\Model;

class RatingType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'type_id'
    ];

    protected $table = 'rating_type';


    // each Rating Type BELONGS to many call audit
    // define our pivot table also
    public function monitorings() {
        return $this->belongsToMany('App\Models\Monitoring', 'monitoring_rating_type', 'rating_type_id', 'monitoring_id');
    }

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // each Rating Type BELONG to many Types 
    public function types() {
        return $this->belongsTo('App\Models\Type','type_id');
    }

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // each Rating Type BELONG to many Types 
    public function ratings() {
        return $this->belongsTo('App\Models\Rating','rating_id');
    }


    public function scopeTypeOf($query,$type) {
        return $query->distinct()->select('rating_type.name')->join('types','rating_type.type_id','=','types.id')->whereRaw("LOWER(types.name) = '".strtolower($type)."'");
    }
    public function scopeDistinctOnly($query) {
        return $query->distinct()->select('name','type_id');
    }
}
