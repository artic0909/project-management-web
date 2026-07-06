<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
     public function index()
    {
        $routePrefix = 'admin';
        $plans = Plan::latest()->get();
        $planCount = $plans->count();
        return view('admin.plans', compact('plans', 'planCount', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            Plan::create([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Plan created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create plan. Please try again.')->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|string|max:255',
        ]);

        try {
            $plan = Plan::findOrFail($id);
            $plan->update([
                'name' => $request->name,
                'created_by' => $request->created_by,
            ]);

            return redirect()->back()->with('success', 'Plan updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Plan not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update plan. Please try again.')->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->delete();

            return redirect()->back()->with('success', 'Plan deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Plan not found.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] === '23000' || $e->getCode() == '23000') {
                return redirect()->back()->with('error', 'Cannot delete plan because it is currently in use by one or more leads/orders.');
            }
            return redirect()->back()->with('error', 'Failed to delete plan. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete plan. Please try again.');
        }
    }
}
