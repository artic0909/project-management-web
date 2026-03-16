<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payment.index');
    }

    public function create()
    {
        return view('admin.payment.create');
    }
}
