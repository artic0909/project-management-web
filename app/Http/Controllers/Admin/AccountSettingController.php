<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function index()
    {
        $routePrefix = 'admin';
        return view('admin.account-settings', compact('routePrefix'));
    }
}
