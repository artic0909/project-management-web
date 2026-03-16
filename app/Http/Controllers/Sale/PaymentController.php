<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('sale.payment.index');
    }

    public function create()
    {
        return view('sale.payment.create');
    }
}
