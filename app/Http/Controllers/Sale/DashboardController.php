<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        $totalLeads = \App\Models\Lead::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function($q) use ($saleId) {
                $q->where('assigned_to', $saleId);
            });
        })->count();

        $totalOrders = \App\Models\Order::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function($q) use ($saleId) {
                $q->where('assigned_to', $saleId);
            });
        })->count();

        $orderIds = \App\Models\Order::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function($q) use ($saleId) {
                $q->where('assigned_to', $saleId);
            });
        })->pluck('id');

        $revenue = \App\Models\Payment::whereIn('order_id', $orderIds)->sum('amount');

        $marketingOrders = \App\Models\Order::whereIn('id', $orderIds)->where('is_marketing', true)->count();

        $totalProjects = \App\Models\Project::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)->where('created_by_type', $saleType);
            })->orWhereHas('salesPersons', function($q) use ($saleId) {
                $q->where('sale_id', $saleId);
            })->orWhereHas('order', function($q) use ($saleId, $saleType) {
                $q->where(function($sq) use ($saleId, $saleType) {
                    $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            });
        })->count();

        return view('sale.dashboard', compact('totalLeads', 'totalOrders', 'revenue', 'marketingOrders', 'totalProjects'));
    }
}
