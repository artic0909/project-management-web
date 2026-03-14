<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketingOrderController extends Controller
{
    public function index()
    {
        return view('sale.marketing-orders');
    }
}
