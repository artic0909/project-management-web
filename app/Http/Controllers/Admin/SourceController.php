<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index()
    {
        $routePrefix = 'admin';
        $sources = Source::latest()->get();
        $sourceCount = $sources->count();
        return view('admin.sources', compact('sources', 'sourceCount', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            Source::create([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Source created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create source. Please try again.')->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            $source = Source::findOrFail($id);
            $source->update([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Source updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Source not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update source. Please try again.')->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $source = Source::findOrFail($id);
            $source->delete();

            return redirect()->back()->with('success', 'Source deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Source not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete source. Please try again.');
        }
    }
}