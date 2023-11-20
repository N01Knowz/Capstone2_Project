<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class manageAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isAdmin');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::where('role', '!=', 'super admin');
        $user = Auth::user();
        if ($user->role !== 'super admin') {
            $users = $users->where('role', '!=', 'admin');
        }
        $users = $users->where('id', '!=', Auth::id());
        if (!empty($search)) {
            $users->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            });
        }
        $users = $users->get();

        $pageType = 'accounts';
        return view('admin.accounts.index', [
            'user_role' => $user->role,
            'users' => $users,
            'pageType' => $pageType,
            'searchInput' => $search,
        ]);
    }
    public function activate($id)
    {
        $user = Auth::user();
        $targetUser = User::find($id);
        if ($targetUser->role == 'super admin') {
            abort(403);
        }
        if ($targetUser->role == 'admin') {
            if ($user->role != 'super admin') {
                abort(403);
            }
            $targetUser->update(['active' => 1]);
        }
        $targetUser->update(['active' => 1]);
        $message = 'User ' . $targetUser->email . ' has been activated';
        return back()->with('message', $message);
    }
    public function deactivate($id)
    {
        $user = Auth::user();
        $targetUser = User::find($id);
        if ($targetUser->role == 'super admin') {
            abort(403);
        }
        if ($targetUser->role == 'admin') {
            if ($user->role != 'super admin') {
                abort(403);
            }
            $targetUser->update(['active' => 0]);
        }
        $targetUser->update(['active' => 0]);
        $message = 'User ' . $targetUser->email . ' has been deactivated';
        return back()->with('message', $message);
    }

    public function destroy(Request $request, $id)
    {

        $requestPassword = $request->input('user-password'); // Get the password from the request
        // Query the database to find "super admin" users
        $superAdmins = User::where('role', 'super admin')
            ->get();

        $superAdminPasswordMatch = false;

        foreach ($superAdmins as $superAdmin) {
            if (Hash::check($requestPassword, $superAdmin->password)) {
                $superAdminPasswordMatch = true;
                break;
            }
        }

        if ($superAdminPasswordMatch) {
            $targetUser = User::find($id);
            $targetUserEmail = $targetUser->email;
            $targetUser->delete();
            $message = 'User ' . $targetUserEmail . ' has been deleted.';
        } else {
            $message = 'Password is Incorrect';
        }

        return back()->with('message', $message);
    }
}
