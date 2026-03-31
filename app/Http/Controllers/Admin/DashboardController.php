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
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // KPI Metrics (Filtered)
        $totalReceivedAmount = Payment::whereBetween('transaction_date', [$startDate, $endDate])->sum('amount');
        
        // Total Order Value for the selected month
        $totalOrderValue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('order_value');
        
        // Total Pending (This is tricky - usually dashboard pending is "Current Balance Due" for that month or overall)
        // I'll keep it as (Total Order Value in month - Total Received in month) for that period.
        $totalPending = max(0, $totalOrderValue - $totalReceivedAmount);
        
        $totalLeads = Lead::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Active Projects Logic (Exclude: complete, completed, canceled, cancelled)
        $activeProjects = Project::whereHas('projectStatus', function($q) {
            $q->whereNotIn('name', ['complete', 'completed', 'canceled', 'cancelled']);
        })->count();
        
        $completedProjects = Project::whereHas('projectStatus', function($q) {
            $q->whereIn('name', ['complete', 'completed']);
        })->count();

        $totalSalesPerson = Sale::count();
        $totalDevelopers = Developer::count();

        // Monthly Data (Last 8 Months ending at the selected date)
        $months = [];
        $monthlyOrderValues = [];
        $monthlyReceivedAmounts = [];

        for ($i = 7; $i >= 0; $i--) {
            $date = $startDate->copy()->subMonths($i);
            $monthName = $date->format('M');
            $yearMonth = $date->format('Y-m');
            
            $months[] = $monthName;
            
            $orderValue = Order::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $yearMonth)->sum('order_value');
            $receivedAmount = Payment::where(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"), $yearMonth)->sum('amount');
            
            $monthlyOrderValues[] = $orderValue;
            $monthlyReceivedAmounts[] = $receivedAmount;
        }

        // Project Pipeline Data (Donut Chart)
        $projectPipeline = Status::where('type', 'order')
            ->get()
            ->map(function($status) {
                return [
                    'name' => $status->name,
                    'count' => Project::where('project_status_id', $status->id)->count(),
                    'color' => $status->color ?? '#6366f1'
                ];
            })->filter(fn($item) => $item['count'] > 0)->values();

        // Fallback for Pipeline if empty
        if ($projectPipeline->isEmpty()) {
            $projectPipeline = Project::select('project_status_id', DB::raw('count(*) as count'))
                ->groupBy('project_status_id')
                ->with('projectStatus')
                ->get()
                ->map(function($p) {
                    return [
                        'name' => $p->projectStatus->name ?? 'Unknown',
                        'count' => $p->count,
                        'color' => $p->projectStatus->color ?? '#6366f1'
                    ];
                });
        }

        $totalProjects = Project::count();
        
        // Data for dropdowns
        $availableYears = range(Carbon::now()->year - 2, Carbon::now()->year + 1);

        return view('admin.dashboard', compact(
            'totalReceivedAmount', 'totalOrderValue', 'totalPending', 'totalLeads', 'totalOrders',
            'activeProjects', 'completedProjects', 'totalSalesPerson', 'totalDevelopers',
            'months', 'monthlyOrderValues', 'monthlyReceivedAmounts',
            'projectPipeline', 'totalProjects', 'selectedMonth', 'selectedYear', 'availableYears'
        ));
    }

    public function allSalesPersonView(){
        return view('admin.sales-person');
    }
}
