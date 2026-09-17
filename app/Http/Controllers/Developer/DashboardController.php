<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Meeting;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dev = auth()->guard('developer')->user();
        $selectedMonth = $request->input('month', 'all');
        if (empty($selectedMonth)) {
            $selectedMonth = 'all';
        }
        $selectedYear = $request->input('year', 'all');
        if (empty($selectedYear)) {
            $selectedYear = 'all';
        }

        $runningProjectsQuery = $dev->projects()
            ->whereHas('projectStatus', function($q) {
                $q->whereNotIn('name', ['complete', 'completed', 'canceled', 'cancelled']);
            });

        $completedProjectsQuery = $dev->projects()
            ->whereHas('projectStatus', function($q) {
                $q->whereIn('name', ['complete', 'completed']);
            });

        $pendingTasksQuery = $dev->tasks()->where('status', '!=', 'Completed');
        $completedTasksQuery = $dev->tasks()->where('status', 'Completed');

        $pendingMeetingsQuery = Meeting::whereJsonContains('assigndev_ids', (string)$dev->id)->where('status', 'pending');
        $completedMeetingsQuery = Meeting::whereJsonContains('assigndev_ids', (string)$dev->id)->where('status', 'completed');

        $attendanceQuery = Attendance::where('user_id', $dev->id)->where('user_type', 'Developer');

        if ($selectedMonth !== 'all' && $selectedYear !== 'all') {
            $startDate = Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $runningProjectsQuery->whereBetween('projects.created_at', [$startDate, $endDate]);
            $completedProjectsQuery->whereBetween('projects.created_at', [$startDate, $endDate]);
            $pendingTasksQuery->whereBetween('project_tasks.created_at', [$startDate, $endDate]);
            $completedTasksQuery->whereBetween('project_tasks.created_at', [$startDate, $endDate]);
            $pendingMeetingsQuery->whereBetween('meeting_date', [$startDate, $endDate]);
            $completedMeetingsQuery->whereBetween('meeting_date', [$startDate, $endDate]);
            $attendanceQuery->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
        } elseif ($selectedMonth !== 'all') {
            $runningProjectsQuery->whereMonth('projects.created_at', (int)$selectedMonth);
            $completedProjectsQuery->whereMonth('projects.created_at', (int)$selectedMonth);
            $pendingTasksQuery->whereMonth('project_tasks.created_at', (int)$selectedMonth);
            $completedTasksQuery->whereMonth('project_tasks.created_at', (int)$selectedMonth);
            $pendingMeetingsQuery->whereMonth('meeting_date', (int)$selectedMonth);
            $completedMeetingsQuery->whereMonth('meeting_date', (int)$selectedMonth);
            $attendanceQuery->whereMonth('date', (int)$selectedMonth);
        } elseif ($selectedYear !== 'all') {
            $runningProjectsQuery->whereYear('projects.created_at', (int)$selectedYear);
            $completedProjectsQuery->whereYear('projects.created_at', (int)$selectedYear);
            $pendingTasksQuery->whereYear('project_tasks.created_at', (int)$selectedYear);
            $completedTasksQuery->whereYear('project_tasks.created_at', (int)$selectedYear);
            $pendingMeetingsQuery->whereYear('meeting_date', (int)$selectedYear);
            $completedMeetingsQuery->whereYear('meeting_date', (int)$selectedYear);
            $attendanceQuery->whereYear('date', (int)$selectedYear);
        }

        $totalRunningProjects = $runningProjectsQuery->count();
        $totalCompletedProjects = $completedProjectsQuery->count();
        $pendingTasks = $pendingTasksQuery->count();
        $completedTasks = $completedTasksQuery->count();
        $pendingMeetings = $pendingMeetingsQuery->count();
        $completedMeetings = $completedMeetingsQuery->count();
        $totalWorkSeconds = $attendanceQuery->sum(DB::raw('ABS(total_seconds)'));

        $availableYears = range(Carbon::now()->year - 4, Carbon::now()->year + 1);
        $routePrefix = 'developer';

        // Fetch closest pending meeting
        $closestMeeting = Meeting::whereIn('status', ['pending', 'rescheduled'])
            ->where('meeting_date', '>=', Carbon::now()->toDateString())
            ->where(function ($q) use ($dev) {
                $q->whereJsonContains('assigndev_ids', (int)$dev->id)
                  ->orWhere('created_by_id', $dev->id)
                  ->where('created_by_type', get_class($dev));
            })
            ->orderBy('meeting_date', 'asc')
            ->orderBy('meeting_time', 'asc')
            ->first();

        return view('admin.dashboard', compact(
            'totalRunningProjects', 'totalCompletedProjects', 
            'pendingTasks', 'completedTasks', 
            'pendingMeetings', 'completedMeetings',
            'selectedMonth', 'selectedYear', 'availableYears',
            'routePrefix', 'totalWorkSeconds', 'closestMeeting'
        ));
    }
}
