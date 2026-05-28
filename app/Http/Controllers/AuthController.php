<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('supplier.index');
        }
        return view('auth.login');
    }

    /**
     * Process login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

           
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user && $user->hasRole('owner')) {
                return redirect()->route('role-permissions.index')->with('success', 'Logged in as Owner.');
            }
            
            return redirect()->route('supplier.index')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    /**
     * Quick Switch role user for development/demo.
     */
    public function quickSwitch($role)
    {
        $emailMap = [
            'owner' => 'owner@sparkstock.com',
            'admin' => 'admin@sparkstock.com',
            'kasir' => 'kasir@sparkstock.com',
            'mekanik' => 'mekanik@sparkstock.com',
        ];

        if (!array_key_exists($role, $emailMap)) {
            return back()->with('error', 'Invalid role selected.');
        }

        $user = User::where('email', $emailMap[$role])->first();

        if ($user) {
            Auth::login($user);
            session()->regenerate();
            if ($role === 'owner') {
                return redirect()->route('role-permissions.index')->with('success', "Switched role to: " . ucfirst($role));
            }
            return redirect()->route('supplier.index')->with('success', "Switched role to: " . ucfirst($role));
        }

        return back()->with('error', 'User for this role not found.');
    }
}
