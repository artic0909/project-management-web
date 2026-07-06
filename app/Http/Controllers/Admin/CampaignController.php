<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $routePrefix = 'admin';
        $campaigns = Campaign::latest()->get();
        $campaignCount = $campaigns->count();
        return view('admin.campaign', compact('campaigns', 'campaignCount', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            Campaign::create([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Campaign created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create campaign. Please try again.')->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->update([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Campaign updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Campaign not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update campaign. Please try again.')->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();

            return redirect()->back()->with('success', 'Campaign deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Campaign not found.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] === '23000' || $e->getCode() == '23000') {
                return redirect()->back()->with('error', 'Cannot delete campaign because it is currently in use by one or more leads.');
            }
            return redirect()->back()->with('error', 'Failed to delete campaign. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete campaign. Please try again.');
        }
    }
}
