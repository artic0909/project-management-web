<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function index()
    {
        $developer = auth()->guard('developer')->user();
        $routePrefix = 'developer';
        return view('admin.account-settings', compact('developer', 'routePrefix'));
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
            'profile_image' => 'nullable|image|max:2048',
            'aadhar_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'pan_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'voter_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'bank_account_pic' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'qualification_attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'designation']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        if (!$developer->kyc_submitted) {
            $kycSubmitted = false;
            if ($request->hasFile('aadhar_card')) { $data['aadhar_card'] = $request->file('aadhar_card')->store('kyc_docs', 'public'); $kycSubmitted = true; }
            if ($request->hasFile('pan_card')) { $data['pan_card'] = $request->file('pan_card')->store('kyc_docs', 'public'); $kycSubmitted = true; }
            if ($request->hasFile('voter_card')) { $data['voter_card'] = $request->file('voter_card')->store('kyc_docs', 'public'); $kycSubmitted = true; }
            if ($request->hasFile('bank_account_pic')) { $data['bank_account_pic'] = $request->file('bank_account_pic')->store('kyc_docs', 'public'); $kycSubmitted = true; }
            
            if ($request->hasFile('qualification_attachments')) {
                $paths = [];
                foreach ($request->file('qualification_attachments') as $file) {
                    $paths[] = $file->store('kyc_docs', 'public');
                }
                $data['qualification_attachments'] = $paths;
                $kycSubmitted = true;
            }
            
            if ($request->filled('phone')) { $data['phone'] = $request->phone; $kycSubmitted = true; }
            if ($request->filled('address')) { $data['address'] = $request->address; $kycSubmitted = true; }
            if ($request->filled('qualification_details')) { $data['qualification_details'] = $request->qualification_details; $kycSubmitted = true; }
            
            if ($kycSubmitted) {
                $data['kyc_submitted'] = true;
            }
        }

        if ($request->filled('new_password')) {
            if (!\Hash::check($request->current_password, $developer->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $data['password'] = \Hash::make($request->new_password);
        }

        $developer->update($data);

        return back()->with('success', 'Profile and settings updated successfully.');
    }
}
