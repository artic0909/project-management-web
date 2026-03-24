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
            $view->with([
                'sourceCount'  => \App\Models\Source::count(),
                'serviceCount' => \App\Models\Service::count(),
                'campaignCount' => \App\Models\Campaign::count(),
                'statusCount' => \App\Models\Status::count(),

                // Add more counts here as your sidebar grows:
                // 'leadCount'    => \App\Models\Lead::count(),
                // 'orderCount'   => \App\Models\Order::count(),
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
