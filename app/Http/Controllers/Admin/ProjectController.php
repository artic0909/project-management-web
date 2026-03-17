<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.project.index');
    }

    public function create()
    {
        return view('admin.project.create');
    }

    public function show()
    {
        return view('admin.project.show');
    }
}
