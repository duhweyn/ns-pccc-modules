<?php

namespace Modules\ReceiptCustomization\Providers;

use App\Classes\Hook;
use App\Events\RenderFooterEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ReceiptCustomizationServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Override the web/print receipt template
        Hook::addFilter( 'ns-web-receipt-template', function ( $defaultView ) {
            return 'ReceiptCustomization::receipt';
        });

        // Register the extra vehicle/document/customer fields
        // so NexoPOS actually saves them onto the order
        Hook::addFilter( 'ns-order-attributes', function ( $attributes ) {
            return array_merge( $attributes, [
                'reference_no',
                'plate_no',
                'vehicle_year',
                'vehicle_make',
                'vehicle_model',
                'vehicle_model_no',
                'vehicle_color',
                'vehicle_chassis_no',
                'prod_date',
                'current_mileage',
                'stock_no',
                'terms',
                'representative',
                'customer_telephone',
                'customer_mobile',
                'customer_fax',
                'selling_dealer',
            ]);
        });

        // Inject the Vehicle Info panel onto the POS screen only
        Event::listen( RenderFooterEvent::class, function ( $event ) {
            if ( $event->routeName === 'ns.dashboard.pos' ) {
                $event->output->addView( 'ReceiptCustomization::pos-vehicle-form' );
            }
        });
    }
}
