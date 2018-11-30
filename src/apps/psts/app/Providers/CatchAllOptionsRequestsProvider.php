<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * If the incoming request is an OPTIONS request
 * we will register a handler for the requested route
 * https://gist.github.com/danharper/06d2386f0b826b669552
 */

class CatchAllOptionsRequestsProvider extends ServiceProvider {
    public function register()
    {
        $request = app('request');
        // \Log::info('METHOD :'.$request->getMethod());
        if ($request->isMethod('OPTIONS')){
            app()->options($request->path(), function() { return response('OK', 200)->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Methods','OPTIONS, GET, POST, PUT, DELETE')
                    ->header('Access-Control-Allow-Headers', 'Content-Type, Origin'); });
        }
    }
}