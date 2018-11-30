<?php

namespace App\Providers;

use App\User;
use Illuminate\Http\Request;
use App\Components\JWT;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        
        // Defined Authentication ServiceProvider
        $this->app['auth']->viaRequest('api', function (Request $request) {
            

            if ($request->hasCookie('token')) {
                // Check if the token is passed as a cookie.
                $token = $request->cookie('token');
            } else {
                // Check if the token is passed using the authorization bearer schema.
                $token = $request->bearerToken();

                if (!$token) {
                    throw new \Exception('Token not found.', 401);
                }
            }
            // Check the token.
            $token = JWT::checkToken($token);

            // Get the party.
            $party = isset($token['party'])?$token['party']:null;
            
            \Cache::put('psts_token_'.$party->username, $request->bearerToken(), config('settings.cache.expires_in'));
            return $party;

        });
    }
}
