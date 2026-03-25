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
        $developers = Developer::latest()->get();
        return view('admin.developers', compact('developers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:developers',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Developer::create([
            'name' => $request->name,
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
            'email' => 'required|string|email|max:255|unique:developers,email,' . $id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:4|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $developer->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

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
}
