<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                return $next($request);
            }
        }

        $this->unauthenticated($request, $guards);
    }

    /**
     * Handle unauthenticated request.
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated', $guards, $this->redirectTo($request)
        );
    }

    /**
     * Redirect path for unauthenticated requests.
     */
    protected function redirectTo($request)
    {
        if ($request->is('developer/*')) {
            return route('developer.login');
        }

        if ($request->is('sales/*')) {
            return route('sales.login');
        }

        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        return route('home');
    }
}   
