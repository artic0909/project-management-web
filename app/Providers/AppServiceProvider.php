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
    }

    private function shareViewData(): void
    {
        // Shares to ALL views (sidebar, layouts, every page)
        View::composer('*', function ($view) {
            $leadCount = 0;
            $orderCount = 0;
            $projectCount = 0;
            $lostLeadCount = 0;
            $sourceCount = \App\Models\Source::count();
            $serviceCount = \App\Models\Service::count();
            $campaignCount = \App\Models\Campaign::count();
            $statusCount = \App\Models\Status::count();
            $developerCount = \App\Models\Developer::count();
            $salesPersonCount = \App\Models\Sale::count();

            if (auth()->guard('admin')->check()) {
                $leadCount = \App\Models\Lead::whereHas('status', function($q){ $q->where('name','!=','lost'); })->count();
                $orderCount = \App\Models\Order::count();
                $lostLeadCount = \App\Models\Lead::whereHas('status', function($q){ $q->where('name','lost'); })->count();
                $projectCount = \App\Models\Project::count();
            } elseif (auth()->guard('sale')->check()) {
                $saleId = auth()->guard('sale')->id();
                $saleType = \App\Models\Sale::class;
                
                $leadCount = \App\Models\Lead::where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($q) use ($saleId) {
                    $q->where('assigned_to', $saleId);
                })->whereHas('status', function($q){ $q->where('name','!=','lost'); })->count();
                
                $orderCount = \App\Models\Order::where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($q) use ($saleId) {
                    $q->where('assigned_to', $saleId);
                })->count();
                
                $lostLeadCount = \App\Models\Lead::where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($q) use ($saleId) {
                    $q->where('assigned_to', $saleId);
                })->whereHas('status', function($q){ $q->where('name','lost'); })->count();
                
                $projectCount = \App\Models\Project::where(function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('salesPersons', function($q) use ($saleId) {
                    $q->where('sale_id', $saleId);
                })->orWhereHas('order', function($q) use ($saleId, $saleType) {
                    $q->where('created_by', $saleId)->where('created_by_type', $saleType)
                      ->orWhereHas('assignments', function($sq) use ($saleId) {
                          $sq->where('assigned_to', $saleId);
                      });
                })->count();
            } elseif (auth()->guard('developer')->check()) {
                $devId = auth()->guard('developer')->id();
                $projectCount = \App\Models\Project::whereHas('developers', function($q) use ($devId) {
                    $q->where('assigned_to', $devId);
                })->count();
            }

            $view->with([
                'sourceCount'  => $sourceCount,
                'serviceCount' => $serviceCount,
                'campaignCount' => $campaignCount,
                'statusCount' => $statusCount,
                'developerCount' => $developerCount,
                'salesPersonCount' => $salesPersonCount,
                'leadCount' => $leadCount,
                'orderCount' => $orderCount,
                'lostLeadCount' => $lostLeadCount,
                'projectCount' => $projectCount,
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
