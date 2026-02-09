<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Redirect based on role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.index');
            }
            return redirect()->route('kasir.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('dashboard.index'));
            }
            return redirect()->intended(route('kasir.index'));
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
