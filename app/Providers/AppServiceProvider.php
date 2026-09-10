<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Source;
use App\Models\Service;
use App\Models\Campaign;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->shareViewData();

        Relation::morphMap([
            'Developer' => \App\Models\Developer::class,
            'Sale'      => \App\Models\Sale::class,
            'Admin'     => \App\Models\Admin::class,
        ]);
    }

    private function shareViewData(): void
    {
        // Shares to ALL views (sidebar, layouts, every page)
        View::composer('*', function ($view) {
            $leadCount = 0;
            $orderCount = 0;
            $projectCount = 0;
            $newProjectCount = 0;
            $activeProjectCount = 0;
            $completeProjectCount = 0;
            $noteCount = 0;
            $taskCount = 0;
            $lostLeadCount = 0;
            $sourceCount = \App\Models\Source::count();
            $serviceCount = \App\Models\Service::count();
            $campaignCount = \App\Models\Campaign::count();
            $planCount = \App\Models\Plan::count();
            $statusCount = \App\Models\Status::count();
            $developerCount = \App\Models\Developer::count();
            $salesPersonCount = \App\Models\Sale::count();
            $meetingCount = 0;
            $supportCount = 0;
            $inquiryCount = 0;
            $invoiceCount = 0;


            $newLeadCount = 0;
            $myLeadCount = 0;
            $totalLeadCount = 0;
            $todayFollowupCount = 0;
            $pendingFollowupCount = 0;
            $futureFollowupCount = 0;
            $totalFollowupCount = 0;
            $upcomingRenewals = collect();
            $unreadTaskReplies = collect();

            if (auth()->guard('admin')->check()) {
                $adminId = auth()->guard('admin')->id();
                $adminType = \App\Models\Admin::class;

                $leadCount = \App\Models\Lead::where('is_losted', 0)->count();
                $totalLeadCount = $leadCount;
                $newLeadCount = \App\Models\Lead::where('is_losted', 0)
                    ->doesntHave('assignments')
                    ->doesntHave('followups')
                    ->count();
                $orderCount = \App\Models\Order::count();
                $lostLeadCount = \App\Models\Lead::where('is_losted', 1)->count();
                $projectCount = \App\Models\Project::count();
                $newProjectCount = \App\Models\Project::whereHas('projectStatus', function ($q) {
                    $q->where('name', 'new');
                })->count();
                $activeProjectCount = \App\Models\Project::whereHas('projectStatus', function ($q) {
                    $q->where('name', '!=', 'complete')->where('name', '!=', 'completed');
                })->count();
                $completeProjectCount = \App\Models\Project::whereHas('projectStatus', function ($q) {
                    $q->whereIn('name', ['complete', 'completed']);
                })->count();
                $noteCount = \App\Models\AdminNote::where('created_by', $adminId)
                    ->where('created_by_type', get_class(auth()->guard('admin')->user()))
                    ->count();
                $meetingCount = \App\Models\Meeting::where('status', 'pending')->count();
                $supportActiveCount = \App\Models\Support::where('status', '!=', 'closed')->count();
                $supportClosedCount = \App\Models\Support::where('status', 'closed')->count();
                $inquiryCount = \App\Models\OrderInquiry::count();
                $newInquiryCount = \App\Models\OrderInquiry::doesntHave('assignments')
                    ->where('status', '!=', 'converted')
                    ->count();
                $totalInquiryCount = \App\Models\OrderInquiry::where(function($q) {
                    $q->whereHas('assignments')->orWhere('status', 'converted');
                })->count();
                $invoiceCount = \App\Models\Invoice::count();

                // Fetch orders with renewal_date within the next 3 days
                $upcomingRenewals = \App\Models\Order::whereBetween('renewal_date', [
                    now()->startOfDay(),
                    now()->addDays(3)->endOfDay()
                ])->get();

                $today = \Carbon\Carbon::today();
                $now = \Carbon\Carbon::now();
                
                $todayFollowupCount = \App\Models\Lead::where('is_losted', 0)
                    ->whereHas('followups', function ($q) use ($today) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', $today);
                    })->count();
                
                $pendingFollowupCount = \App\Models\Lead::where('is_losted', 0)
                    ->whereHas('followups', function ($q) use ($today) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', '<', $today);
                    })->count();
                
                $futureFollowupCount = \App\Models\Lead::where('is_losted', 0)
                    ->whereHas('followups', function ($q) use ($today) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', '>', $today);
                    })->count();

                $totalFollowupCount = \App\Models\Lead::where('is_losted', 0)
                    ->whereHas('followups', function ($q) {
                        $q->where('followable_type', \App\Models\Lead::class);
                    })->count();

                // Read notifications specific to this Admin
                $readFollowupIds = \App\Models\NotificationRead::where('user_type', $adminType)
                    ->where('user_id', $adminId)
                    ->where('item_type', 'followup')
                    ->pluck('item_id');

                $todayTimedFollowups = \App\Models\Lead::where('is_losted', 0)
                    ->whereHas('followups', function ($q) use ($today, $readFollowupIds) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', $today)
                          ->whereTime('next_schedule_date', '!=', '00:00:00')
                          ->where('next_schedule_date', '<=', \Carbon\Carbon::now()->addMinutes(15))
                          ->whereNotIn('id', $readFollowupIds);
                    })->with(['followups' => function($q) {
                        $q->orderBy('id', 'desc');
                    }])->get();

                // Developer Task Replies for Admin (Admin sees all task replies from developers)
                $readTaskAssignIds = \App\Models\NotificationRead::where('user_type', $adminType)
                    ->where('user_id', $adminId)
                    ->where('item_type', 'task_assign')
                    ->pluck('item_id');

                $unreadTaskReplies = \App\Models\ProjectTaskAssign::whereNotNull('remarks')
                    ->where('remarks', '!=', '')
                    ->whereNotIn('id', $readTaskAssignIds)
                    ->whereHas('task')
                    ->with(['task.project', 'task.creator', 'developer'])
                    ->latest('updated_at')
                    ->take(15)
                    ->get();

            } elseif (auth()->guard('sale')->check()) {
                $saleId = auth()->guard('sale')->id();
                $saleType = \App\Models\Sale::class;
                
                $leadCount = \App\Models\Lead::where(function($q) use ($saleId, $saleType) {
                    $q->where(function($sq) use ($saleId, $saleType) {
                        $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('assignments', function($sq) use ($saleId) {
                        $sq->where('assigned_to', $saleId);
                    });
                })->where('is_losted', 0)->count();

                $newLeadCount = \App\Models\Lead::where('is_losted', 0)
                    ->doesntHave('assignments')
                    ->doesntHave('followups')
                    ->count();

                $myLeadCount = \App\Models\Lead::where('is_losted', 0)
                    ->where(function ($q) {
                        $q->whereHas('status', function ($sq) {
                            $sq->where('name', '!=', 'Converted');
                        })->orWhereNull('status_id');
                    })
                    ->whereHas('assignments', function($sq) use ($saleId) {
                        $sq->where('assigned_to', $saleId);
                    })->count();

                $totalLeadCount = \App\Models\Lead::where('is_losted', 0)->count();
                
                $orderCount = \App\Models\Order::where(function($q) use ($saleId, $saleType) {
                    $q->where(function($sq) use ($saleId, $saleType) {
                        $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('assignments', function($sq) use ($saleId) {
                        $sq->where('assigned_to', $saleId);
                    });
                })->count();
                
                $lostLeadCount = \App\Models\Lead::whereHas('assignments', function($q) use ($saleId) {
                    $q->where('assigned_to', $saleId);
                })->where('is_losted', 1)->count();
                
                $saleProjectQuery = \App\Models\Project::where(function ($master) use ($saleId, $saleType) {
                    $master->where(function ($q) use ($saleId, $saleType) {
                        $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('salesPersons', function ($q) use ($saleId) {
                        $q->where('sale_id', $saleId);
                    })->orWhereHas('order', function ($q) use ($saleId, $saleType) {
                        $q->where(function ($sq) use ($saleId, $saleType) {
                            $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                        })->orWhereHas('assignments', function ($sq) use ($saleId) {
                            $sq->where('assigned_to', $saleId);
                        });
                    });
                });
                
                $projectCount = (clone $saleProjectQuery)->count();
                
                $newProjectCount = (clone $saleProjectQuery)->whereHas('projectStatus', function ($q) {
                    $q->where('name', 'new');
                })->count();
                
                $activeProjectCount = (clone $saleProjectQuery)->whereHas('projectStatus', function ($q) {
                    $q->where('name', '!=', 'complete')->where('name', '!=', 'completed');
                })->count();
                
                $completeProjectCount = (clone $saleProjectQuery)->whereHas('projectStatus', function ($q) {
                    $q->whereIn('name', ['complete', 'completed']);
                })->count();
                
                $noteCount = \App\Models\AdminNote::where('created_by', auth()->guard('sale')->id())
                    ->where('created_by_type', get_class(auth()->guard('sale')->user()))
                    ->count();

                $meetingCount = \App\Models\Meeting::whereJsonContains('assignsale_ids', (int)$saleId)
                    ->where('status', 'pending')->count();

                // Fetch upcoming renewals for sales person
                $upcomingRenewals = \App\Models\Order::where(function($q) use ($saleId, $saleType) {
                    $q->where(function($sq) use ($saleId, $saleType) {
                        $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                    })->orWhereHas('assignments', function($sq) use ($saleId) {
                        $sq->where('assigned_to', $saleId);
                    });
                })->whereBetween('renewal_date', [
                    now()->startOfDay(),
                    now()->addDays(3)->endOfDay()
                ])->get();

                $newInquiryCount = \App\Models\OrderInquiry::doesntHave('assignments')
                    ->where('status', '!=', 'converted')
                    ->count();
                $myInquiryCount = \App\Models\OrderInquiry::whereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                })->count();

                $today = \Carbon\Carbon::today();
                $now = \Carbon\Carbon::now();
                
                $baseSaleLeadQuery = \App\Models\Lead::where('is_losted', 0)
                    ->whereHas('assignments', function($q) use ($saleId) {
                        $q->where('assigned_to', $saleId);
                    });

                $todayFollowupCount = (clone $baseSaleLeadQuery)
                    ->whereHas('followups', function ($q) use ($today) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', $today);
                    })->count();
                
                $pendingFollowupCount = (clone $baseSaleLeadQuery)
                    ->whereHas('followups', function ($q) use ($today) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', '<', $today);
                    })->count();
                
                $futureFollowupCount = (clone $baseSaleLeadQuery)
                    ->whereHas('followups', function ($q) use ($today) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', '>', $today);
                    })->count();

                $totalFollowupCount = (clone $baseSaleLeadQuery)
                    ->whereHas('followups', function ($q) {
                        $q->where('followable_type', \App\Models\Lead::class);
                    })->count();

                // Read notifications specific to this Sales person
                $readFollowupIds = \App\Models\NotificationRead::where('user_type', $saleType)
                    ->where('user_id', $saleId)
                    ->where('item_type', 'followup')
                    ->pluck('item_id');

                $todayTimedFollowups = (clone $baseSaleLeadQuery)
                    ->whereHas('followups', function ($q) use ($today, $readFollowupIds) {
                        $q->whereIn('id', function($sub) {
                            $sub->selectRaw('max(id)')->from('followups')
                                ->whereColumn('followable_id', 'leads.id')
                                ->where('followable_type', \App\Models\Lead::class);
                        })->whereDate('next_schedule_date', $today)
                          ->whereTime('next_schedule_date', '!=', '00:00:00')
                          ->where('next_schedule_date', '<=', \Carbon\Carbon::now()->addMinutes(15))
                          ->whereNotIn('id', $readFollowupIds);
                    })->with(['followups' => function($q) {
                        $q->orderBy('id', 'desc');
                    }])->get();

                // Developer Task Replies for Sales person (Only for tasks created by this Sales person)
                $readTaskAssignIds = \App\Models\NotificationRead::where('user_type', $saleType)
                    ->where('user_id', $saleId)
                    ->where('item_type', 'task_assign')
                    ->pluck('item_id');

                $unreadTaskReplies = \App\Models\ProjectTaskAssign::whereNotNull('remarks')
                    ->where('remarks', '!=', '')
                    ->whereNotIn('id', $readTaskAssignIds)
                    ->whereHas('task', function($q) use ($saleId, $saleType) {
                        $q->where('created_by', $saleId)
                          ->where(function($sq) use ($saleType) {
                              $sq->where('created_by_type', $saleType)
                                 ->orWhere('created_by_type', 'Sale');
                          });
                    })
                    ->with(['task.project', 'task.creator', 'developer'])
                    ->latest('updated_at')
                    ->take(15)
                    ->get();

            } elseif (auth()->guard('developer')->check()) {
                $devId = auth()->guard('developer')->id();
                $devProjectQuery = \App\Models\Project::whereHas('developers', function($q) use ($devId) {
                    $q->where('assigned_to', $devId);
                });
                
                $projectCount = (clone $devProjectQuery)->count();
                
                $newProjectCount = (clone $devProjectQuery)->whereHas('projectStatus', function ($q) {
                    $q->where('name', 'new');
                })->count();
                
                $activeProjectCount = (clone $devProjectQuery)->whereHas('projectStatus', function ($q) {
                    $q->where('name', '!=', 'complete')->where('name', '!=', 'completed');
                })->count();
                
                $completeProjectCount = (clone $devProjectQuery)->whereHas('projectStatus', function ($q) {
                    $q->whereIn('name', ['complete', 'completed']);
                })->count();
                
                $noteCount = \App\Models\AdminNote::where('created_by', auth()->guard('developer')->id())
                    ->where('created_by_type', get_class(auth()->guard('developer')->user()))
                    ->count();

                $meetingCount = \App\Models\Meeting::whereJsonContains('assigndev_ids', (int)$devId)
                    ->where('status', 'pending')->count();
                    
                $supportActiveCount = \App\Models\Support::where('status', '!=', 'closed')->count();
                $supportClosedCount = \App\Models\Support::where('status', 'closed')->count();
                
                $taskCount = \App\Models\ProjectTask::whereHas('assignments', function($q) use ($devId) {
                    $q->where('developer_id', (int)$devId);
                })->where('status', '!=', 'Completed')->count();
            }

            $view->with([
                'sourceCount'  => $sourceCount,
                'serviceCount' => $serviceCount,
                'planCount' => $planCount,
                'campaignCount' => $campaignCount,
                'statusCount' => $statusCount,
                'developerCount' => $developerCount,
                'salesPersonCount' => $salesPersonCount,
                'leadCount' => $leadCount,
                'newLeadCount' => $newLeadCount,
                'myLeadCount' => $myLeadCount,
                'totalLeadCount' => $totalLeadCount,
                'orderCount' => $orderCount,
                'lostLeadCount' => $lostLeadCount,
                'projectCount' => $projectCount,
                'newProjectCount' => $newProjectCount,
                'activeProjectCount' => $activeProjectCount,
                'completeProjectCount' => $completeProjectCount,
                'noteCount' => $noteCount,
                'meetingCount' => $meetingCount,
                'taskCount' => $taskCount,
                'supportActiveCount' => $supportActiveCount ?? 0,
                'supportClosedCount' => $supportClosedCount ?? 0,
                'inquiryCount' => $inquiryCount ?? 0,
                'newInquiryCount' => $newInquiryCount ?? 0,
                'totalInquiryCount' => $totalInquiryCount ?? 0,
                'myInquiryCount' => $myInquiryCount ?? 0,
                'invoiceCount' => $invoiceCount ?? 0,
                'upcomingRenewals' => $upcomingRenewals,
                'todayTimedFollowups' => $todayTimedFollowups ?? collect(),
                'unreadTaskReplies' => $unreadTaskReplies ?? collect(),
                'todayFollowupCount' => $todayFollowupCount ?? 0,
                'pendingFollowupCount' => $pendingFollowupCount ?? 0,
                'futureFollowupCount' => $futureFollowupCount ?? 0,
                'totalFollowupCount' => $totalFollowupCount ?? 0,
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
