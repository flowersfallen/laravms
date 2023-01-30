<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PermissionDeniedException;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = Auth::guard('admin')->id();
        if ($id != 1) {
            throw new PermissionDeniedException();
        }

        return $next($request);
    }
}
