<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Sale;
use App\Models\Developer;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $routePrefix = auth()->guard('admin')->check() ? 'admin' : 'sale';
        $user = auth()->guard($routePrefix)->user();
        $saleId = ($routePrefix == 'sale') ? $user->id : null;
        $saleType = \App\Models\Sale::class;

        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Base Query Scoping
        $paymentQuery = Payment::whereBetween('transaction_date', [$startDate, $endDate]);
        $orderQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $leadQuery = Lead::whereBetween('created_at', [$startDate, $endDate]);
        $projectQuery = Project::query();

        if ($routePrefix == 'sale') {
            $paymentQuery->whereHas('order', function($master) use ($saleId, $saleType) {
                $master->where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            });

            $orderQuery->where(function($master) use ($saleId, $saleType) {
                $master->where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            });

            $leadQuery->where(function($master) use ($saleId, $saleType) {
                $master->where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            });

            $projectQuery->where(function($master) use ($saleId, $saleType) {
                $master->where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('salesPersons', function($sq) use ($saleId) {
                    $sq->where('sale_id', $saleId);
                })->orWhereHas('order', function($sq) use ($saleId, $saleType) {
                    $sq->where(function($ssq) use ($saleId, $saleType) {
                        $ssq->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('assignments', function($ssq) use ($saleId) {
                        $ssq->where('assigned_to', $saleId);
                    });
                });
            });
        }

        // KPI Metrics (Filtered)
        $totalReceivedAmount = $paymentQuery->sum('amount');
        $totalOrderValue = $orderQuery->sum('order_value');
        $totalPending = max(0, $totalOrderValue - $totalReceivedAmount);
        
        $totalLeads = $leadQuery->count();
        $totalOrders = $orderQuery->count();
        
        // Active Projects Logic
        $activeProjects = (clone $projectQuery)->whereHas('projectStatus', function($q) {
            $q->whereNotIn('name', ['complete', 'completed', 'canceled', 'cancelled']);
        })->count();
        
        $completedProjects = (clone $projectQuery)->whereHas('projectStatus', function($q) {
            $q->whereIn('name', ['complete', 'completed']);
        })->count();

        $totalSalesPerson = Sale::count();
        $totalDevelopers = Developer::count();

        // CHART DATA (Keeping it for both since it's nice, but scoped)
        $months = [];
        $monthlyOrderValues = [];
        $monthlyReceivedAmounts = [];

        for ($i = 7; $i >= 0; $i--) {
            $date = $startDate->copy()->subMonths($i);
            $monthName = $date->format('M');
            $yearMonth = $date->format('Y-m');
            $months[] = $monthName;
            
            $mo_orderQuery = Order::query()->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $yearMonth);
            $mo_paymentQuery = Payment::query()->where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $yearMonth);

            if ($routePrefix == 'sale') {
                $mo_orderQuery->where(function($master) use ($saleId, $saleType) {
                    $master->where(function($q) use ($saleId, $saleType) {
                        $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('assignments', function($sq) use ($saleId) {
                        $sq->where('assigned_to', $saleId);
                    });
                });

                $mo_paymentQuery->whereHas('order', function($master) use ($saleId, $saleType) {
                    $master->where(function($q) use ($saleId, $saleType) {
                        $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('assignments', function($sq) use ($saleId) {
                        $sq->where('assigned_to', $saleId);
                    });
                });
            }

            $monthlyOrderValues[] = $mo_orderQuery->sum('order_value');
            $monthlyReceivedAmounts[] = $mo_paymentQuery->sum('amount');
        }

        // Project Pipeline Data
        $projectPipeline = Status::where('type', 'order')->get()->map(function($status) use ($projectQuery) {
            return [
                'name' => $status->name,
                'count' => (clone $projectQuery)->where('project_status_id', $status->id)->count(),
                'color' => $status->color ?? '#6366f1'
            ];
        })->filter(fn($item) => $item['count'] > 0)->values();

        $totalProjects = (clone $projectQuery)->count();
        $marketingOrders = (clone $orderQuery)->where('is_marketing', true)->count();
        $availableYears = range(Carbon::now()->year - 2, Carbon::now()->year + 1);

        return view('admin.dashboard', compact(
            'totalReceivedAmount', 'totalOrderValue', 'totalPending', 'totalLeads', 'totalOrders',
            'activeProjects', 'completedProjects', 'totalSalesPerson', 'totalDevelopers',
            'months', 'monthlyOrderValues', 'monthlyReceivedAmounts', 'marketingOrders',
            'projectPipeline', 'totalProjects', 'selectedMonth', 'selectedYear', 'availableYears', 'routePrefix'
        ));
    }

    public function allSalesPersonView(){
        return view('admin.sales-person');
    }
}
