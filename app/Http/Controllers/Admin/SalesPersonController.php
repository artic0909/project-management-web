<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesPersonController extends Controller
{
    public function index()
    {
        return view('admin.sales-person');
    }
}
