<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        return view('sale.leads.index');
    }

    public function create()
    {
        return view('sale.leads.create');
    }

    public function edit()
    {
        return view('sale.leads.edit');
    }

    public function lostedLeads()
    {
        return view('sale.losted-leads');
    }
}
