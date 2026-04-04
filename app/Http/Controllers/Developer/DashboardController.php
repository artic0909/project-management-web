<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dev = auth()->guard('developer')->user();
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Total Running Projects (assigned to dev, not in complete/canceled)
        $totalRunningProjects = $dev->projects()
            ->whereHas('projectStatus', function($q) {
                $q->whereNotIn('name', ['complete', 'completed', 'canceled', 'cancelled']);
            })
            ->whereBetween('projects.created_at', [$startDate, $endDate])
            ->count();

        // Total Completed Projects (assigned to dev, in complete/completed)
        $totalCompletedProjects = $dev->projects()
            ->whereHas('projectStatus', function($q) {
                $q->whereIn('name', ['complete', 'completed']);
            })
            ->whereBetween('projects.created_at', [$startDate, $endDate])
            ->count();

        // Pending Tasks
        $pendingTasks = $dev->tasks()
            ->where('status', '!=', 'Completed')
            ->whereBetween('project_tasks.created_at', [$startDate, $endDate])
            ->count();

        // Completed Tasks
        $completedTasks = $dev->tasks()
            ->where('status', 'Completed')
            ->whereBetween('project_tasks.created_at', [$startDate, $endDate])
            ->count();

        // Meetings logic (Assigndev_ids contains dev ID)
        $pendingMeetings = Meeting::whereJsonContains('assigndev_ids', (string)$dev->id)
            ->where('status', 'pending')
            ->whereBetween('meeting_date', [$startDate, $endDate])
            ->count();
        
        $completedMeetings = Meeting::whereJsonContains('assigndev_ids', (string)$dev->id)
            ->where('status', 'completed')
            ->whereBetween('meeting_date', [$startDate, $endDate])
            ->count();

        $availableYears = range(Carbon::now()->year - 2, Carbon::now()->year + 1);
        $routePrefix = 'developer';

        return view('developer.dashboard', compact(
            'totalRunningProjects', 'totalCompletedProjects', 
            'pendingTasks', 'completedTasks', 
            'pendingMeetings', 'completedMeetings',
            'selectedMonth', 'selectedYear', 'availableYears',
            'routePrefix'
        ));
    }
}
