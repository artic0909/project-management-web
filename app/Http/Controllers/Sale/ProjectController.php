<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('sale.project.index');
    }

    public function create()
    {
        return view('sale.project.create');
    }
    
    public function show()
    {
        return view('sale.project.show');
    }
}
