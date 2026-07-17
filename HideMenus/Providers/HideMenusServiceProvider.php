<?php

namespace Modules\HideMenus\Providers;

use App\Classes\Hook;
use Illuminate\Support\ServiceProvider;

class HideMenusServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Hook::addFilter( 'ns-dashboard-menus', function ( $menus ) {
            unset( $menus['taxes']['childrens']['taxes-groups'] );
            unset( $menus['taxes']['childrens']['create-taxes-group'] );
            unset( $menus['modules']['childrens']['upload-module'] );
            unset( $menus['modules']['childrens']['marketplace'] );
            return $menus;
        });
    }
}
