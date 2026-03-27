<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sale;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SalesPersonController extends Controller
{
    public function index()
    {
        $salesPeople = Sale::latest()->paginate(14)->withQueryString();
        return view('admin.sales-person', compact('salesPeople'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sales',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Sale::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Sales Person added successfully!');
    }

    public function edit(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sales,email,' . $id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:4|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sale->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $sale->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->back()->with('success', 'Sales Person updated successfully!');
    }

    public function delete($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->back()->with('success', 'Sales Person deleted successfully!');
    }
}
