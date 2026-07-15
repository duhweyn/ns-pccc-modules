<?php

namespace Modules\NsPcccTheme\Providers;

use App\Events\RenderFooterEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as RootServiceProvider;

class NsPcccThemeServiceProvider extends RootServiceProvider
{
    /**
     * This NexoPOS build injects footer content via a Laravel event
     * (RenderFooterEvent), not the older Hook::addAction( 'ns-dashboard-footer' )
     * filter system. Listening here gets our compiled theme CSS added
     * to every dashboard page's footer output.
     */
    public function boot()
    {
        Event::listen( RenderFooterEvent::class, function ( RenderFooterEvent $event ) {
            $event->output->addView( 'NsPcccTheme::theme-injector' );
        } );
    }

    public function register()
    {
        //
    }
}
