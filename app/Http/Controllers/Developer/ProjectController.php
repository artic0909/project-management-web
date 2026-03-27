<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->guard('developer')->user()->projects();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('project_name', 'like', "%$search%")
                  ->orWhere('company_name', 'like', "%$search%")
                  ->orWhere('client_name', 'like', "%$search%")
                  ->orWhere('domain_name', 'like', "%$search%");
            });
        }

        $projects = $query->latest()->get();
        return view('developer.project.index', compact('projects'));
    }

    public function show($projectId)
    {
        $project = auth()->guard('developer')->user()->projects()->findOrFail($projectId);
        return view('developer.project.show', compact('project'));
    }
}
