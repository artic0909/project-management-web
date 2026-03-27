<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function index()
    {
        $developer = auth()->guard('developer')->user();
        return view('developer.account-settings', compact('developer'));
    }

    public function update(Request $request)
    {
        $developer = auth()->guard('developer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:developers,email,' . $developer->id,
            'designation' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'designation']);

        if ($request->filled('new_password')) {
            if (!\Hash::check($request->current_password, $developer->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $data['password'] = \Hash::make($request->new_password);
        }

        $developer->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
