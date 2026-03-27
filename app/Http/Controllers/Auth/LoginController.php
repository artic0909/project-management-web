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

    public function adminProfileAndPasswordUpdate(Request $request)
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|required_with:current_password|confirmed',
        ]);

        // Profile Update
        $admin->name = $request->name;
        $admin->email = $request->email;

        // Password Update
        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match.']);
            }
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $admin->save();

        return back()->with('success', 'Profile and password updated successfully!');
    }

    public function saleProfileAndPasswordUpdate(Request $request)
    {
        $sale = \Illuminate\Support\Facades\Auth::guard('sale')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales,email,' . $sale->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|required_with:current_password|confirmed',
        ]);

        // Profile Update
        $sale->name = $request->name;
        $sale->email = $request->email;

        // Password Update
        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $sale->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match.']);
            }
            $sale->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $sale->save();

        return back()->with('success', 'Profile and password updated successfully!');
    }
}
