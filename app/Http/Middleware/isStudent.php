<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            // Check if the user's role is 'student'
            if ($user->role !== 'student') {
                // If the role is not 'student', abort with a 403 response
                abort(403, 'Access denied.');
            }
        }
        return $next($request);
    }
}
