<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $routePrefix = 'admin';
        $services = Service::latest()->get();
        $serviceCount = $services->count();
        return view('admin.services', compact('services', 'serviceCount', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            Service::create([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create Service. Please try again.')->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            $Service = Service::findOrFail($id);
            $Service->update([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Service updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Service not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Service. Please try again.')->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $Service = Service::findOrFail($id);
            $Service->delete();

            return redirect()->back()->with('success', 'Service deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Service not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete Service. Please try again.');
        }
    }
}
