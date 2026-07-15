<?php

namespace Modules\NsPcccDashboardFix\Providers;

use App\Services\ReportService;
use Illuminate\Support\ServiceProvider as RootServiceProvider;
use Modules\NsPcccDashboardFix\Services\FixedReportService;

class NsPcccDashboardFixServiceProvider extends RootServiceProvider
{
    /**
     * Registering (rather than booting) the binding makes sure it's in
     * place before anything else in the request resolves ReportService
     * out of the container — controllers, the report listener, and the
     * "ns:report" console command all just ask for ReportService and
     * will transparently receive our corrected subclass instead.
     */
    public function register()
    {
        $this->app->bind( ReportService::class, FixedReportService::class );
    }
}
