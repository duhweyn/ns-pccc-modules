<?php

namespace Modules\NsPcccTheme\Providers;

use App\Classes\Hook;
use Illuminate\Support\ServiceProvider as RootServiceProvider;

class NsPcccThemeServiceProvider extends RootServiceProvider
{
    /**
     * Runs on every request. We hook into "ns-dashboard-footer" so our
     * compiled theme CSS gets injected on every dashboard page, without
     * touching a single core file.
     */
    public function boot()
    {
        Hook::addAction('ns-dashboard-footer', function ($output) {
            $output->addView('NsPcccTheme::theme-injector');
        });
    }

    public function register()
    {
        //
    }
}
