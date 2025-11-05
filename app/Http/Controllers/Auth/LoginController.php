<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Direct database authentication since we have the data
        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if ($user && password_verify($request->password, $user->password)) {
            // Create a simple session
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'branch_id' => $user->branch_id,
                'role_id' => $user->role_id,
                'logged_in' => true
            ]);

            return redirect()->route('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->with('error', 'Invalid credentials or account inactive.');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}