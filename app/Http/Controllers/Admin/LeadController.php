<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        return view('admin.leads.index');
    }

    public function create()
    {
        return view('admin.leads.create');
    }

    public function edit()
    {
        return view('admin.leads.edit');
    }

    public function lostedLeads()
    {
        return view('admin.losted-leads');
    }
}
