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
use App\Models\Followup;
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

        $selectedMonth = $request->input('month', 'all');
        if (empty($selectedMonth)) {
            $selectedMonth = 'all';
        }
        $selectedYear = $request->input('year', 'all');
        if (empty($selectedYear)) {
            $selectedYear = 'all';
        }

        // Base Query Scoping
        $paymentQuery = Payment::query();
        $orderQuery = Order::query();
        $leadQuery = Lead::query();
        $projectQuery = Project::query();

        if ($selectedMonth !== 'all' && $selectedYear !== 'all') {
            $startDate = Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $paymentQuery->whereBetween('transaction_date', [$startDate, $endDate]);
            $orderQuery->whereBetween('created_at', [$startDate, $endDate]);
            $leadQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($selectedMonth !== 'all') {
            $paymentQuery->whereMonth('transaction_date', (int)$selectedMonth);
            $orderQuery->whereMonth('created_at', (int)$selectedMonth);
            $leadQuery->whereMonth('created_at', (int)$selectedMonth);
        } elseif ($selectedYear !== 'all') {
            $paymentQuery->whereYear('transaction_date', (int)$selectedYear);
            $orderQuery->whereYear('created_at', (int)$selectedYear);
            $leadQuery->whereYear('created_at', (int)$selectedYear);
        }

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

        if ($selectedYear !== 'all' && $selectedMonth !== 'all') {
            $baseDate = Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->startOfMonth();
        } elseif ($selectedYear !== 'all') {
            $baseDate = Carbon::create((int)$selectedYear, 12, 1)->startOfMonth();
        } else {
            $baseDate = Carbon::now()->startOfMonth();
        }

        for ($i = 7; $i >= 0; $i--) {
            $date = $baseDate->copy()->subMonths($i);
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
        $projectStatusColors = [
            'New' => '#3b82f6',
            'Design Phase' => '#8b5cf6',
            'Development' => '#f59e0b',
            'Testing' => '#06b6d4',
            'Complete' => '#10b981',
            'On Hold' => '#ef4444',
            'Closed' => '#64748b',
            'Cancel' => '#dc2626',
        ];

        $projectPipeline = Status::where('type', 'project')->get()->map(function($status) use ($projectQuery, $projectStatusColors) {
            $count = (clone $projectQuery)->where(function($q) use ($status) {
                $q->where('project_status_id', $status->id)
                  ->orWhere('project_status', $status->name);
            })->count();
            return [
                'id' => $status->id,
                'name' => $status->name,
                'count' => $count,
                'color' => $projectStatusColors[$status->name] ?? '#6366f1'
            ];
        })->values();

        $totalProjects = (clone $projectQuery)->count();
        $marketingOrders = (clone $orderQuery)->where('is_marketing', true)->count();
        $availableYears = range(Carbon::now()->year - 2, Carbon::now()->year + 1);

        // 1. LEAD FUNNEL DATA
        $applyLeadDateFilter = function($query, $dateCol = 'created_at') use ($selectedMonth, $selectedYear) {
            if ($selectedMonth !== 'all' && $selectedYear !== 'all') {
                $startDate = Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                $query->whereBetween($dateCol, [$startDate, $endDate]);
            } elseif ($selectedMonth !== 'all') {
                $query->whereMonth($dateCol, (int)$selectedMonth);
            } elseif ($selectedYear !== 'all') {
                $query->whereYear($dateCol, (int)$selectedYear);
            }
        };

        $applyLostDateFilter = function($query) use ($selectedMonth, $selectedYear) {
            if ($selectedMonth !== 'all' && $selectedYear !== 'all') {
                $startDate = Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                $query->whereBetween(DB::raw('COALESCE(losted_date, updated_at, created_at)'), [$startDate, $endDate]);
            } elseif ($selectedMonth !== 'all') {
                $query->whereMonth(DB::raw('COALESCE(losted_date, updated_at, created_at)'), (int)$selectedMonth);
            } elseif ($selectedYear !== 'all') {
                $query->whereYear(DB::raw('COALESCE(losted_date, updated_at, created_at)'), (int)$selectedYear);
            }
        };

        if ($routePrefix == 'sale') {
            $totalLeadsAllQuery = Lead::where('is_losted', 0);
            $applyLeadDateFilter($totalLeadsAllQuery);
            $funnelTotal = $totalLeadsAllQuery->count();

            $myLeadsQuery = Lead::where('is_losted', 0)
                ->where(function ($q) {
                    $q->whereHas('status', function ($sq) {
                        $sq->where('name', '!=', 'Converted');
                    })->orWhereNull('status_id');
                })
                ->whereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            $applyLeadDateFilter($myLeadsQuery);
            $funnelMyLeads = $myLeadsQuery->count();

            $newLeadsQuery = Lead::where('is_losted', 0)
                ->doesntHave('assignments')
                ->doesntHave('followups');
            $applyLeadDateFilter($newLeadsQuery);
            $funnelNew = $newLeadsQuery->count();

            $lostLeadsQuery = Lead::where('is_losted', 1)
                ->whereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            $applyLostDateFilter($lostLeadsQuery);
            $funnelLost = $lostLeadsQuery->count();

            $convertedQuery = Lead::whereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                })->where(function($q) {
                    $q->whereHas('status', fn($sq) => $sq->where('name', 'converted'))
                      ->orWhereIn('id', Order::whereNotNull('lead_id')->pluck('lead_id'));
                });
            $applyLeadDateFilter($convertedQuery);
            $funnelConverted = $convertedQuery->count();

            $totalSaleLeads = $funnelMyLeads + $funnelConverted;
            $conversionRate = $totalSaleLeads > 0 ? round(($funnelConverted / $totalSaleLeads) * 100, 1) : 0;

            $leadFunnel = [
                'total' => $funnelTotal,
                'my_leads' => $funnelMyLeads,
                'new' => $funnelNew,
                'lost' => $funnelLost,
                'converted' => $funnelConverted,
                'conversion_rate' => $conversionRate,
            ];
        } else {
            $totalLeadsAllQuery = Lead::where('is_losted', 0);
            $applyLeadDateFilter($totalLeadsAllQuery);
            $funnelTotal = $totalLeadsAllQuery->count();

            $newLeadsQuery = Lead::where('is_losted', 0)
                ->doesntHave('assignments')
                ->doesntHave('followups');
            $applyLeadDateFilter($newLeadsQuery);
            $funnelNew = $newLeadsQuery->count();

            $convertedQuery = Lead::where(function($q) {
                $q->whereHas('status', fn($sq) => $sq->where('name', 'converted'))
                  ->orWhereIn('id', Order::whereNotNull('lead_id')->pluck('lead_id'));
            });
            $applyLeadDateFilter($convertedQuery);
            $funnelConverted = $convertedQuery->count();

            $lostLeadsQuery = Lead::where('is_losted', 1);
            $applyLostDateFilter($lostLeadsQuery);
            $funnelLost = $lostLeadsQuery->count();

            $conversionRate = $funnelTotal > 0 ? round(($funnelConverted / $funnelTotal) * 100, 1) : 0;

            $leadFunnel = [
                'total' => $funnelTotal,
                'new' => $funnelNew,
                'converted' => $funnelConverted,
                'lost' => $funnelLost,
                'conversion_rate' => $conversionRate,
            ];
        }

        $totalLeads = $funnelTotal;

        // 2. FOLLOWUPS BREAKDOWN (Pie/Donut Chart)
        $today = Carbon::today();
        $leadFollowupQuery = Lead::where('is_losted', 0);
        if ($routePrefix == 'sale') {
            $leadFollowupQuery->where(function($master) use ($saleId, $saleType) {
                $master->where('created_by', $saleId)->where('created_by_type', $saleType)
                       ->orWhereHas('assignments', function($sq) use ($saleId) {
                           $sq->where('assigned_to', $saleId);
                       });
            });
        }

        $todayFollowups = (clone $leadFollowupQuery)->whereHas('followups', function($q) use ($today) {
            $q->whereIn('id', function($sub) {
                $sub->selectRaw('max(id)')->from('followups')->whereColumn('followable_id', 'leads.id')->where('followable_type', Lead::class);
            })->whereDate('next_schedule_date', $today);
        })->count();

        $pendingFollowups = (clone $leadFollowupQuery)->whereHas('followups', function($q) use ($today) {
            $q->whereIn('id', function($sub) {
                $sub->selectRaw('max(id)')->from('followups')->whereColumn('followable_id', 'leads.id')->where('followable_type', Lead::class);
            })->whereDate('next_schedule_date', '<', $today);
        })->count();

        $futureFollowups = (clone $leadFollowupQuery)->whereHas('followups', function($q) use ($today) {
            $q->whereIn('id', function($sub) {
                $sub->selectRaw('max(id)')->from('followups')->whereColumn('followable_id', 'leads.id')->where('followable_type', Lead::class);
            })->whereDate('next_schedule_date', '>', $today);
        })->count();

        $totalFollowups = $todayFollowups + $pendingFollowups + $futureFollowups;

        // Channel / Communication Mode Breakdown (Total all-time followups on leads, scoped by Admin or Sales POV)
        $channelFollowupQuery = Followup::where('followable_type', Lead::class);
        if ($routePrefix == 'sale') {
            $scopedLeadIds = Lead::where(function($master) use ($saleId, $saleType) {
                $master->where('created_by', $saleId)->where('created_by_type', $saleType)
                       ->orWhereHas('assignments', function($sq) use ($saleId) {
                           $sq->where('assigned_to', $saleId);
                       });
            })->select('id');

            $channelFollowupQuery->whereIn('followable_id', $scopedLeadIds);
        }

        $callingFollowups = (clone $channelFollowupQuery)->where('followup_type', 'Calling')->count();
        $msgFollowups = (clone $channelFollowupQuery)->where('followup_type', 'Message')->count();
        $bothFollowups = (clone $channelFollowupQuery)->where('followup_type', 'Both')->count();

        $followupStats = [
            'today' => $todayFollowups,
            'pending' => $pendingFollowups,
            'future' => $futureFollowups,
            'total' => $totalFollowups,
            'calling' => $callingFollowups,
            'message' => $msgFollowups,
            'both' => $bothFollowups,
        ];

        // 3. MONTHLY ORDERS DATA (Vertical Bar Chart)
        $monthlyOrderCounts = [];
        $monthlyWebOrderCounts = [];
        $monthlyMktOrderCounts = [];

        for ($i = 7; $i >= 0; $i--) {
            $date = $baseDate->copy()->subMonths($i);
            $yearMonth = $date->format('Y-m');
            
            $mo_orderQuery = Order::query()->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $yearMonth);
            if ($routePrefix == 'sale') {
                $mo_orderQuery->where(function($master) use ($saleId, $saleType) {
                    $master->where('created_by', $saleId)->where('created_by_type', $saleType)
                           ->orWhereHas('assignments', function($sq) use ($saleId) {
                               $sq->where('assigned_to', $saleId);
                           });
                });
            }
            
            $webOrders = (clone $mo_orderQuery)->where(function($q) {
                $q->where('is_marketing', 0)->orWhereNull('is_marketing');
            })->count();
            $mktOrders = (clone $mo_orderQuery)->where('is_marketing', 1)->count();
            
            $monthlyWebOrderCounts[] = $webOrders;
            $monthlyMktOrderCounts[] = $mktOrders;
            $monthlyOrderCounts[] = $webOrders + $mktOrders;
        }

        // Fetch closest pending meeting
        $meetingQuery = \App\Models\Meeting::whereIn('status', ['pending', 'rescheduled'])
            ->where('meeting_date', '>=', Carbon::now()->toDateString());

        if ($routePrefix == 'sale') {
            $meetingQuery->where(function ($q) use ($user) {
                $q->whereJsonContains('assignsale_ids', (int)$user->id)
                  ->orWhere('created_by_id', $user->id)
                  ->where('created_by_type', get_class($user));
            });
        }

        $closestMeeting = $meetingQuery->orderBy('meeting_date', 'asc')
            ->orderBy('meeting_time', 'asc')
            ->first();

        return view('admin.dashboard', compact(
            'totalReceivedAmount', 'totalOrderValue', 'totalPending', 'totalLeads', 'totalOrders',
            'activeProjects', 'completedProjects', 'totalSalesPerson', 'totalDevelopers',
            'months', 'monthlyOrderValues', 'monthlyReceivedAmounts', 'marketingOrders',
            'projectPipeline', 'totalProjects', 'selectedMonth', 'selectedYear', 'availableYears', 'routePrefix', 'closestMeeting',
            'leadFunnel', 'followupStats', 'monthlyOrderCounts', 'monthlyWebOrderCounts', 'monthlyMktOrderCounts'
        ));
    }

    public function allSalesPersonView(){
        return view('admin.sales-person');
    }
}
