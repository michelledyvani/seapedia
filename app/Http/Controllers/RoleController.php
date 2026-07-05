<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function selectForm()
    {
        $user  = Auth::user();
        $roles = $user->getRoleNames();
        if (count($roles) === 1) {
            session(['active_role' => $roles[0]]);
            return redirect()->route('dashboard.' . $roles[0]);
        }
        return view('role.select', compact('roles'));
    }

    public function setRole(Request $request)
    {
        $request->validate(['role' => 'required|in:buyer,seller,driver,admin']);
        $user = Auth::user();
        if (!$user->hasRole($request->role)) {
            return back()->withErrors(['role' => 'Role tidak valid.']);
        }
        session(['active_role' => $request->role]);
        return redirect()->route('dashboard.' . $request->role);
    }
}
