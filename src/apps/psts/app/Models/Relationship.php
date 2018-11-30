<?php
namespace F3\Models;

use DB;
use F3\Components\Model;

class Relationship extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'user_group_role';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['user_id', 'group_id','role_id', 'created_by', 'updated_by'];

    /**
     * The table's primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Returns the model validation rules.
     */
    public function getRules()
    {
        return [
            'user_id' => 'integer|required|exists:users,id',
            'group_id' => 'integer|required|exists:groups,id',
            'role_id' => 'integer|required|exists:roles,id'
        ];
    }

    /**
     * Creates a new relationship between two parties.
     * @param int $user_id From users ID
     * @param int $group_id From groups ID
     * @param int $role_id From roles ID
     */
    public static function store($user_id, $group_id,$role_id, $created_by)
    {
        // Build the attribute list.
        $attributes = [
            'user_id' => $user_id,
            'group_id' => $group_id
            'role_id' => $role_id,
            'created_by' => $created_by
        ];

        // Create the relationship.
        return self::create($attributes);
    }
}
