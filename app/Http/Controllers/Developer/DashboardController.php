<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dev = auth()->guard('developer')->user();
        
        $totalProjects = $dev->projects()->count();
        $openTasks = $dev->tasks()->where('status', '!=', 'Completed')->count();
        $completedTasks = $dev->tasks()->where('status', 'Completed')->count();

        return view('developer.dashboard', compact('totalProjects', 'openTasks', 'completedTasks'));
    }
}
