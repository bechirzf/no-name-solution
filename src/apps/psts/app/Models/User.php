<?php
namespace App\Models;

use DB;
use Log;
use Illuminate\Support\Facades\Hash;
use App\Components\Model;
use App\Components\JWT;

class User extends Model
{
    /**
     * RBAC trait.
     * This class is shared between the User and Group models.
     */
    use \App\Components\RBAC;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['user_id','employee_id','name','email','username','password', 'roles', 'manager_id', 'office_id', 'position_id', 'department_id', 'api_key', 'secret_key'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Returns the model validation rules.
     */
    public function getRules()
    {
        return [];
    }
    /**
     * Validates the user's credentials.
     * Authenticate user,group and his role.
     * 
     * @param string $login_id -> username
     * @param string $password
     * @param string $role
     * @param array $extra = []
     * 
     * @return array
     * 
     * 
     * @throws \Exception
     */
    public static function authenticate($login_id, $password, $extra = [])
    {
        $user = DB::table('users as u')
                    ->select([
                        'u.*',
                        'k.api_key', 
                        'k.secret_key', 
                    ])
                    ->join('api_keys as k', 'k.party_id', '=', 'u.id')
                    ->where([['u.username', $login_id], ['u.deleted_at', null]])
                    ->first();

        // Check if the user exists.
        if (!$user) {
            throw new \Exception('The credentials that you provided are invalid or your account may have been disabled.', 401);
        }

        $result = json_decode(json_encode($user), true);

        // Check the password.
        if (!Hash::check($password, $result['password'])) {
            throw new \Exception('The credentials that you provided are invalid or your account may have been disabled.', 401);
        }
        
        $token = User::generateAndUpdateToken($result['id'], $result['api_key'], $result['secret_key']);

        $result['token'] = $token;

        // Remove the password and return the user.
        unset($result['password']);

        return $result;
    }

    /**
     * Creates a new user.
     */
    public static function store()
    {
        try {
            // Build the attribute list.
            $attributes = [];

            // Create the user. 
            return self::create($attributes);
        } catch (\Exception $e) {
            // Rollback and return the error.
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Returns the party by API key.
     * 
     */
    public static function getByApiKey($api_key)
    {
        // Get the party by key.
        $party = DB::table('api_keys as k')
            ->select(['p.id as user_id','p.manager_id','p.employee_id','p.email','p.username', 'k.api_key', 'k.secret_key'])
            ->join('users as p', 'p.id', '=', 'k.party_id')
            ->where([['k.api_key', $api_key], ['k.status', 1], ['p.deleted_at', null]])
            ->first();
        if ($party) {
            $result = json_decode(json_encode($party), true);
            $object = '\App\Models\\' . ucfirst('user');
            return new $object($result);
        } else {
            return false;
        }
    }
    /**
     * Generate the token for the user.
     * 
     * @param String $partyId
     * @param String $apiKey
     * @param String $secretKey
     * @return string Token 
     */
    public static function generateAndUpdateToken($partyId, $apiKey, $secretKey){
      
        $token = JWT::createToken([
            'iat' => time(),
            'party_id' => $partyId,
            'sub' => $apiKey,
            // 'exp' => time()+3600
            ], $secretKey
        );

        ApiKey::updateToken(
            [   
                'token' => $token, 
                'token_expires_at' => date('Y-m-d', strtotime('+30 days'))
            ],
            $partyId);
        

        // Log::debug("token: " . $token);
        
        return $token;
        
    }

    /**
     * Process the Roster Feed into the Users table
     * 
     * @param array $data []
     * 
     * @return string token
     */
    public static function processRosterFeed($data)
    {
        Log::info('Start - '.__METHOD__);
        
        // Save Party User
        $user = self::register($data);

        $token = ApiKey::generateKeys($user->getKey(),$data['email']);

        Log::debug('Data ||===>' . print_r($token,1));

        return  $token;
    }

    /**
     * Creates a new user.
     * 
     */
    public static function register($attributes  = [])
    {
        Log::info('Start - ' . __METHOD__);
        // Start the transaction.
        DB::beginTransaction();

        try {
            // Build the attribute list.
            $attributes['password']=Hash::make(env('DEFAULT_PASSWORD', 'psts2018')); // override the password
            
            // Create the user. 
            $user = self::create($attributes);
            // Commit and return the user.
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            // Rollback and return the error.
            Log::error('Exception: '. $e->getMessage());
            DB::rollback();
            throw $e;
        }
    }
    

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function skills() {
        return $this->belongsToMany('App\Models\Skill', 'user_skill');
    }

    // DEFINE RELATIONSHIPS --------------------------------------------------
    // define a many to many relationship
    // also call the linking table
    public function schedules() {

        return $this->belongsToMany('App\Models\Schedule', 'user_schedule')->withPivot('days');
    }
    // each user has many 1on1 coaching
    public function coachings() {
        return $this->hasMany('App\Models\Main');
    }
    // each user has many call audits
    public function callAudits() {
        return $this->hasMany('App\Models\Monitoring');
    }

    // each user has one manager
    public function manager() {
        return $this->hasOne('App\Models\Manager','id','manager_id');
    }

    // each user has one position
    public function position() {
        return $this->hasOne('App\Models\Position','id','position_id');
    }

    public function scopeNotManager($query,$managers)
    {
       return $query->select(['id','name'])->whereNotIn('email',$managers);
    }

}
