<?php
namespace App\Models;

use DB;
use App\Components\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name', 'description', 'user_id', 'created_by','updated_by'];

    /**
     * Returns the model validation rules.
     */
    public function getRules()
    {
        return [
            'user_id' => 'integer|required|exists:users,id',
            'name' => 'string|required|max:255|unique:groups,name',
            'description' =>  'string|required',
            'created_by' => 'string|required',
            'updated_by' =>  'string|nullable'
        ];
    }

    // each Group BELONGS to many users
    // define our pivot table also
    public function users() {
        return $this->belongsToMany('App\Models\User', 'user_group_role', 'group_id', 'user_id');
    }

}
