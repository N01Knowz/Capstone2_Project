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
            // dd(session('success'));
            if (session()->has('success')) {
                // dd("Hello");
                if ($user->role === 'student') {
                    return redirect()->route('taketestIndex')->with('success', session('success'));
                } elseif ($user->role === 'teacher') {
                    return redirect()->route('mcq.index')->with('success', session('success'));
                } elseif ($user->role === 'admin') {
                    return redirect()->route('accountsIndex')->with('success', session('success'));
                } elseif ($user->role === 'super admin') {
                    return redirect()->route('accountsIndex')->with('success', session('success'));
                }
            }
            if ($user->role === 'student') {
                return redirect()->route('taketestIndex');
            } elseif ($user->role === 'teacher') {
                return redirect()->route('mcq.index');
            } elseif ($user->role === 'admin') {
                return redirect()->route('accountsIndex');
            } elseif ($user->role === 'super admin') {
                return redirect()->route('accountsIndex');
            }
        }

        return $next($request);
    }
}
