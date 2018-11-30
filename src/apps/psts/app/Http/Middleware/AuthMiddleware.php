<?php

namespace App\Http\Middleware;

/**
 *  handles authorization for controllers
 * 
 *
 */

use Illuminate\Http\Request;
use App\Components\Response;
use App\Components\JWT;
use Closure;
use DB;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null, $permission=null)
    {

        // Logging of  Query in development or staging environment
        if (preg_match('/(dev|stg|staging)i/', config('settings.env'))){
            DB::connection()->enableQueryLog();
        }

        try {
            if ($request->hasCookie('token')) {
                // Check if the token is passed as a cookie.
                $token = $request->cookie('token');
            } else {
                // Check if the token is passed using the authorization bearer schema.
                $token = $request->bearerToken();
                // \Log::info('TOKEN : '.$token);
                if (!$token) {
                    throw new \Exception('Token not found.', 401);
                }
            }
            
            // Check the token.
            $token = JWT::checkToken($token);

            // if ($role) {
            //     // Check the role.
            //     if (!$party->hasRole(explode('|', $role))) {
            //         throw new \Exception('You do not have sufficient privileges to access this resource.', 401);
            //     }
            // }

            $request->attributes->set('role', $role);

            return $next($request);

        } catch (\Exception $e) {
            \Log::error('MiddleWareException: ' . $e->getMessage());
            return Response::exception($e);
        }
    }
    /**
     * Split the action name from the a controller method.
     * 
     * @return array
     * @created 4/18/2017
     */
    public function getActions($method,$new)
    {
        $arr = str_split($method);
        if(count($arr) > 1) {
            foreach($arr as $w => $v)
            {
                if(ctype_upper($v) && $w !== 0)
                {
                    array_push($new,str_split($method,$w)[0]);
                    return $this->getActions(substr($method, $w),$new);
                }
            }
            array_push($new,$method);
        }
        return $new;
    }
    /**
     * Get the data input field identifier for the module/controller to update/create.
     * 
     * @return string
     * @created 4/18/2017
     */
    public function getUniqueParam($data,$name)
    {
        // List of Controllers
        $controllers = [
                    'Admin|Customer' => 'email',
                    'Branch|Cms|Promotion|Setting|Warehouse' => 'name',
                    'Coupon' => 'code',
                ];
        foreach ($controllers as $key => $i) {
            if(preg_match('/'.$name.'/i', $key)){
                $value =  isset($data[$i]) ? ' / '.str_replace('_', " ", $data[$i]):'';
                // if $i is set to name then capitalize each word of the value
                return $i === 'name' ? ucwords($value):$value;
            }
        }
        return '';
    }
}