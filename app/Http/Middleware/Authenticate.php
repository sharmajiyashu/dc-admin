<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // if($request->ajax()){
        //     return response()->json([
        //         'statusCode' => 401,
        //         'status' => 'unauthrozed',
        //         'message' => 'Unauthrized user'
        //     ]);
        // }
        if (! $request->expectsJson()) {
            return route('login');
        }
       
    }
}
