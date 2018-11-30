<?php

namespace App\Models;

use App\Components\JWT;
use App\Components\Model;
use Log;
use DB;

class ApiKey extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'party_id',
        'api_key', 
        'secret_key',
        'token',
        'token_expires_at',
        'status',
        'created_at',
        'expires_at', 
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    
    public $timestamps = false;
    
    public function getRules(){

        Log::info('Start - '.__METHOD__);
        return [
            'party_id' => 'integer|required',
            'api_key' => 'string|required',
            'secret_key' => 'string|required',
        ];
    }
    
    
    /**
     * Update the token and token_expiers_at
     *  
     * @param array $attributes
     * @param type $party_id
     * @return type
     */
    public static function updateToken(array $attributes, $party_id)
    {
        return self::where('party_id', $party_id)
            ->update($attributes);
    }
    
    /**
     * Creates a new api token.
     */
    public static function store(array $attributes)
    {
        Log::info('Start - ' . __METHOD__);
        // Start the transaction.
        DB::beginTransaction();
        try {
            // Build the attribute list.
            $attributes['created_at'] = date('c'); 
            // Create the ApiKey. 
            $apikey = self::create($attributes);
            // Commit and return the api key.
            DB::commit();
            return $apikey;
        } catch (\Exception $e) {
            // Rollback and return the error.
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Generate Keys and Store keys
     * 
     * @param str|int $key -. Normally the party id
     * @param string $name -> name of the key. Sometimes email.
     */
    public static function generateKeys($partyId, $email){
        
        $keys = __generate_api_key($partyId);
        
        $token = JWT::createToken([
            'iat' => time(),
            'party_id' => $partyId,
            'sub' => $keys['api_key'],
            // 'exp' => time()+3600
        ], $keys['secret_key']);
        
        $keys['party_id'] = $partyId;
        $keys['name'] = $email;
        $keys['token'] = $token;
        $keys['expires_at'] = date('Y-m-d', strtotime('+1 days'));
        // Log::info('Data ===>' .print_r($keys,1));
        try{
            return self::store($keys);
        }  catch(\Exception $e){
            throw new \Exception('Cant store new keys.'."\n".'Message: '.$e->getMessage());
        }
        
    }
}
