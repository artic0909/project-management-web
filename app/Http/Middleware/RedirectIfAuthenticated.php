<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
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
                switch ($guard) {
                    case 'developer':
                        return redirect()->route('developer.dashboard');
                    case 'sale':
                        return redirect()->route('sale.dashboard');
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    default:
                        return redirect('/');
                }
            }
        }

        return $next($request);
    }
}
