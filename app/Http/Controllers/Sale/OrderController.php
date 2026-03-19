<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('sale.orders.index');
    }

    public function create()
    {
        return view('sale.orders.create');
    }

    public function edit()
    {
        return view('sale.orders.edit');
    }
}
