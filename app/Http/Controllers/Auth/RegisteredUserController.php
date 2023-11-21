<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use function Laravel\Prompts\error;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function choice(): View
    {
        return view('auth.register-roles');
    }

    public function create($role): View
    {
        if (!in_array($role, ['teacher', 'student', 'admin'])) {
            abort(404);
        }
        if ($role == 'admin') {
            if (Auth::check()) {
                $user = Auth::user();
                // Check if the user's role is 'super admin'
                if ($user->role !== 'super admin') {
                    abort(403);
                }
            } else {
                abort(403);
            }
        }
        if (isset($user)) {
            return view('auth.register', [
                'role' => $role,
                'user_role' => $user->role,
            ]);
        } else {
            return view('auth.register', [
                'role' => $role,
            ]);
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $role): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'first_name' => ucfirst($request->first_name),
            'last_name' => ucfirst($request->last_name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        event(new Registered($user));
        if ($role == 'admin') {
            return redirect('/accounts')->with('success', 'Registered Successfully, verification sent to ' . $request->email);
        } else {
            Auth::login($user);

            return redirect('/redirect')->with('success', 'Registered Successfully');
        }
    }
}
