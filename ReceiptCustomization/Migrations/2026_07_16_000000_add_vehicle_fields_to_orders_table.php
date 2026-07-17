<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table( 'nexopos_orders', function ( Blueprint $table ) {
            // Document info
            $table->string( 'reference_no' )->nullable();
            $table->string( 'plate_no' )->nullable();

            // Vehicle info
            $table->string( 'vehicle_year' )->nullable();
            $table->string( 'vehicle_make' )->nullable();
            $table->string( 'vehicle_model' )->nullable();
            $table->string( 'vehicle_model_no' )->nullable();
            $table->string( 'vehicle_color' )->nullable();
            $table->string( 'vehicle_chassis_no' )->nullable();
            $table->string( 'prod_date' )->nullable();
            $table->string( 'current_mileage' )->nullable();

            // Stock / terms
            $table->string( 'stock_no' )->nullable();
            $table->string( 'terms' )->nullable();

            // Customer contact / representative (all optional)
            $table->string( 'representative' )->nullable();
            $table->string( 'customer_telephone' )->nullable();
            $table->string( 'customer_mobile' )->nullable();
            $table->string( 'customer_fax' )->nullable();

            // Dealer
            $table->string( 'selling_dealer' )->nullable();
        });
    }

    public function down(): void
    {
        Schema::table( 'nexopos_orders', function ( Blueprint $table ) {
            $table->dropColumn([
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
    }
};
