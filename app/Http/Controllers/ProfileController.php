<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, User $user)
    {
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        $input = $request->all();
        // Validate the incoming data
        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);
        // dd($request->file("imageInput"));

        // dd("HELLO");
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dataToFill = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ];

        $randomName = "";
        if ($request->hasFile('imageInput')) {
            do {
                $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = User::where('user_image', $randomName)->first();
            } while ($existingImage);
            $imagePath = public_path('user_upload_images/' . $user->user_image);
            if (File::exists($imagePath)) {
                // Delete the image file
                File::delete($imagePath);

                // Optionally, you can also remove the image filename from the database or update the question record here
            }
            $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
            $dataToFill['user_image'] = $randomName;
        }

        $user->fill($dataToFill);


        $user->save();


        return redirect('/profile');
    }

    public function new_password(Request $request): View
    {
        return view('profile.new_password', [
            'user' => $request->user(),
        ]);
    }

    public function update_password(Request $request)
    {
        $user = User::find(Auth::id());

        // Validate the input
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()->mixedCase()->numbers()],
        ]);
    
        // Check if the current password is correct
        if (Hash::check($request->current_password, $user->password)) {
            // Update the password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
    
            // Logout the user
            Auth::logout();
    
            return redirect('/login')->with('password_changed', 'Password changed successfully. You have been logged out.');
        }
    
        return redirect()->back()->with('error', 'Current password is incorrect.');
    }
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
