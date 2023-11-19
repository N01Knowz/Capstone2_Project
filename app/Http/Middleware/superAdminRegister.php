<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class superAdminRegister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            // Check if the user's role is 'student'
            if ($user->role !== 'super admin') {
                $guards = empty($guards) ? [null] : $guards;

                foreach ($guards as $guard) {
                    if (Auth::guard($guard)->check()) {
                        return redirect(RouteServiceProvider::HOME);
                    }
                }
            }
        }
        return $next($request);
    }
}
