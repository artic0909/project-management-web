<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Developer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    public function index()
    {
        $routePrefix = 'admin';
        $developers = Developer::latest()->paginate(14)->withQueryString();
        return view('admin.developer.index', compact('developers', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:developers',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Developer::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Developer added successfully!');
    }

    public function edit(Request $request, $id)
    {
        $developer = Developer::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:developers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'aadhar_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'pan_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'voter_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'bank_account_pic' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'qualification_details' => 'nullable|string',
            'qualification_attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:4|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'designation', 'email', 'phone', 'address', 'qualification_details']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }
        if ($request->hasFile('aadhar_card')) {
            $data['aadhar_card'] = $request->file('aadhar_card')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('pan_card')) {
            $data['pan_card'] = $request->file('pan_card')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('voter_card')) {
            $data['voter_card'] = $request->file('voter_card')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('bank_account_pic')) {
            $data['bank_account_pic'] = $request->file('bank_account_pic')->store('kyc_docs', 'public');
        }
        if ($request->hasFile('qualification_attachments')) {
            $paths = $developer->qualification_attachments ?? [];
            foreach ($request->file('qualification_attachments') as $file) {
                $paths[] = $file->store('kyc_docs', 'public');
            }
            $data['qualification_attachments'] = $paths;
        }

        $developer->update($data);

        if ($request->filled('password')) {
            $developer->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->back()->with('success', 'Developer updated successfully!');
    }

    public function delete($id)
    {
        $developer = Developer::findOrFail($id);
        $developer->delete();

        return redirect()->back()->with('success', 'Developer deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:developers,id',
        ]);

        Developer::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected Developers deleted successfully!');
    }
}
