<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Components\Response;
use Closure;

/**
 * This class will normally validate the cross domain calls
 * 
 * @class App\Http\Middleware\CorsMiddleware
 */
class CorsMiddleware
{
    /**
     * Handle an incoming request.
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Fetch the origin.
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : false;
            
            $xip = $request->server('HTTP_X_FORWARDED_FOR');
            $ip = $request->server('REMOTE_ADDR');
            // \Log::info('ORIGIN : '.$origin.' XIP : '.$xip.' IP : '.$ip);
            if (in_array($xip, config('settings.cors.allowed_ips')) || in_array($ip, config('settings.cors.allowed_ips'))) {
                // Origin is allowed. Add the access control headers.
                return $next($request)
                    ->header('Access-Control-Allow-Origin', "*")
                    ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, DELETE')
                    ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Authorization, ApiKey, Accept')
                    ->header('Access-Control-Allow-Credentials', 'true');
            }elseif (in_array($origin, config('settings.cors.allowed_origins'))) {
                // Origin is allowed. Add the access control headers.
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, DELETE')
                    ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Authorization, ApiKey, Accept')
                    ->header('Access-Control-Allow-Credentials', 'true');

            } else {
                // Origin is not allowed.
                // Call the next middleware/controller.
                return $next($request);
            }
        } catch (\Exception $e) {
            return Response::exception($e);
        }
    }
}
