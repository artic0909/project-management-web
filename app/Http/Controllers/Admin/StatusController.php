<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $statuses     = Status::latest()->get();
        $statusCount  = $statuses->count();
        $leadStatusCount    = $statuses->where('type', 'lead')->count();
        $orderStatusCount   = $statuses->where('type', 'order')->count();
        $paymentStatusCount = $statuses->where('type', 'payment')->count();
        $projectStatusCount = $statuses->where('type', 'project')->count();

        $routePrefix = 'admin';
        return view('admin.status', compact(
            'statuses', 'statusCount',
            'leadStatusCount', 'orderStatusCount', 'paymentStatusCount', 'projectStatusCount',
            'routePrefix'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:lead,order,payment,project',
        ]);

        try {
            Status::create([
                'name' => $request->name,
                'type' => $request->type,
            ]);

            return redirect()->back()->with('success', 'Status "' . $request->name . '" added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add status. Please try again.')
                ->with('open_modal', 'addStatusModal')
                ->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:lead,order,payment,project',
        ]);

        try {
            $status = Status::findOrFail($id);
            $status->update([
                'name' => $request->name,
                'type' => $request->type,
            ]);

            return redirect()->back()->with('success', 'Status updated to "' . $request->name . '" successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Status not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update status. Please try again.')
                ->with('open_modal', 'editStatusModal')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $status = Status::findOrFail($id);
            $name   = $status->name;
            $status->delete();

            return redirect()->back()->with('success', 'Status "' . $name . '" deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Status not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete status. Please try again.');
        }
    }
}