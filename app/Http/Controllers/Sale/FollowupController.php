<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    public function index()
    {
        return view('sale.followup');
    }
}
