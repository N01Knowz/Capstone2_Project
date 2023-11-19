<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'student') {
                return redirect('/taketest');
            } elseif ($user->role === 'teacher') {
                return redirect('/mcq');
            } elseif ($user->role === 'admin') {
                return redirect('/accounts');
            } elseif ($user->role === 'super admin') {
                return redirect('/accounts');
            }
        }

        return $next($request);
    }
}
