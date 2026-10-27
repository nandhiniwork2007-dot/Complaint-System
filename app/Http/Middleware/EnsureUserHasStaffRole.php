<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasStaffRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
<<<<<<< HEAD
        if (!$request->user() || !$request->user()->isStaff()) {
=======
        if (! $request->user() || ! $request->user()->isStaff()) {
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
            abort(403, 'You are not authorized to access the admin portal.');
        }

        return $next($request);
    }
}
