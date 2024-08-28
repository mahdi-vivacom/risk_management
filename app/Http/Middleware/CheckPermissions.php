<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$permissions
     * @return mixed
     */
    public function handle ( $request, Closure $next )
    {
        $user       = Auth::user ();
        $routeName  = $request->route ()->getName ();
        if ( !$user->can ( $routeName ) ) {
            throw UnauthorizedException::forPermissions ( [ $routeName ] );
        }
        return $next ( $request );
    }
}
