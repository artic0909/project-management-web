<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided admin credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function saleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::guard('sale')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('sale.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided sale credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function developerLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::guard('developer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('developer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided developer credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
        } elseif (\Illuminate\Support\Facades\Auth::guard('sale')->check()) {
            \Illuminate\Support\Facades\Auth::guard('sale')->logout();
        } elseif (\Illuminate\Support\Facades\Auth::guard('developer')->check()) {
            \Illuminate\Support\Facades\Auth::guard('developer')->logout();
        } else {
            \Illuminate\Support\Facades\Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
