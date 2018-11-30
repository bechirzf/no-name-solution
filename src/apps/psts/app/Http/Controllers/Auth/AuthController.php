<?php

namespace App\Http\Controllers\Auth;

use App\Components\Response;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Cache;
use Log;

class AuthController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    /**
     * Registration Validator
     * @param array $data
     * @return type
     */
    protected function validator(array $data)
    {
        $rules = [
            'username'     => 'required|string|max:255|exists:users,username',
            'password'  => 'required|min:6'
        ];
        
        return Validator::make($data, $rules, [
            'username.*'  => trans('messages.auth.422'),
            'password.*'  => trans('messages.auth.422'),
        ]);
    }
    
    /**
     * Login Authentication Start here.
     * 
     * @param Request $request
     */
    public function login(Request $request){
        Log::info("Start - " . __METHOD__);
        try{
            $data=$this->getData($request);
            Log::info("passed data :==> ".print_r($data,1));
            Log::info("JSON ::".$request->isJson());
            // change username lower case
            if(isset($data['username'])){
                if(!empty($data['username'])){
                  $data['username'] = strtolower($data['username']);
                }
            }
            $validator = $this->validator($data);

            if ($validator->fails()) {
                $this->throwValidationException($request, $validator);
            }

            $user = User::authenticate($data['username'], $data['password']); 

            return Response::success(200, "Success", ["data" => $user]);
            
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }
    /**
     * Test.
     * 
     * @param Request $request
     */
    public function register(Request $request){
        Log::info("Start - " . __METHOD__);
        try{
            $data=$this->getData($request);

            // change username lower case
            if(isset($data['username'])){
                if(!empty($data['username'])){
                  $data['username'] = strtolower($data['username']);
                }
            }
            $validator = $this->validator($data);

            if ($validator->fails()) {
                $this->throwValidationException($request, $validator);
            }
            $user = User::where('username',$data['username'])->first();

            // Check the password.
            if (!Hash::check($data['password'], $user['password'])) {
                throw new \Exception('The credentials that you provided are invalid or your account may have been disabled.', 401);
            }

            $result['keys'] = ApiKey::generateKeys($user['id'],$user['email']);
            $result['users'] = $user; 

            return Response::success(200, "Success", ["data" => $result]);
            
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    } 
    /**
     * Delete the current Token. for this sessions.
     * 
     * @param Request $request
     * @return type
     */
    public function logout(Request $request)
    {
        // Delete cache
        // Cache::forget($request->user()->api_key); 
        
        // Response::success(200, '');
        
    }
}

