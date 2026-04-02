<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function index()
    {
        $routePrefix = 'sale';
        return view('admin.account-settings', compact('routePrefix'));
    }
}
