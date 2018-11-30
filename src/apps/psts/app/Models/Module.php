<?php

namespace App\Models;

use App\Components\Model;

class Module extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'url',
        'type_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the type that owns the module.
     */
    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }
}
