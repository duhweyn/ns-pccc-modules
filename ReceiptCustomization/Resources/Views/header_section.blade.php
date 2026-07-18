@php
    // Secondary/billing logo: dropped directly into the module's own files
    // rather than pulled from NexoPOS settings. Place a file named
    // "billing-logo" (.png, .jpg, .jpeg, or .svg) inside:
    //   Modules/ReceiptCustomization/Resources/Images/
    // and it will show up here automatically — no settings, no publish step.
    $billingLogoDir = ( function_exists( 'module_path' ) ? module_path( 'ReceiptCustomization' ) : base_path( 'Modules/ReceiptCustomization' ) ) . '/Resources/Images';
    $billingLogoDataUri = null;

    foreach ( [ 'png', 'jpg', 'jpeg', 'svg' ] as $ext ) {
        $candidate = $billingLogoDir . '/billing-logo.' . $ext;
        if ( is_file( $candidate ) ) {
            $mime = $ext === 'svg' ? 'image/svg+xml' : ( $ext === 'png' ? 'image/png' : 'image/jpeg' );
            $billingLogoDataUri = 'data:' . $mime . ';base64,' . base64_encode( file_get_contents( $candidate ) );
            break;
        }
    }
@endphp
<div style="border:1px solid #000; padding:10px; font-family: Arial, sans-serif; font-size:11px;">
    <div style="display:flex; justify-content:space-between; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:8px;">
        {{-- Left: Logo + Company Info + Powered By --}}
        <div style="width:55%;">
            <div style="display:flex; align-items:center; gap:12px;">
                @if ( ! empty( ns()->option->get( 'ns_invoice_receipt_logo' ) ) )
                <img src="{{ ns()->option->get( 'ns_invoice_receipt_logo' ) }}"
                     alt="{{ ns()->option->get( 'ns_store_name' ) }}"
                     style="max-width:100px; max-height:80px; object-fit:contain; flex-shrink:0;">
                @endif
                <div>
                    <div style="font-size:16px; font-weight:bold; margin-bottom:2px;">{{ ns()->option->get( 'ns_store_name' ) }}</div>
                    <div>{{ ns()->option->get( 'ns_store_address' ) }}, {{ ns()->option->get( 'ns_store_city' ) }}</div>
                    @if( ns()->option->get( 'ns_store_additional' ) )
                    <div>VAT Reg. TIN: {{ ns()->option->get( 'ns_store_additional' ) }}</div>
                    @endif
                    <div>Email: {{ ns()->option->get( 'ns_store_email' ) }}</div>
                    <div>Telephone: {{ ns()->option->get( 'ns_store_phone' ) }}</div>
                </div>
            </div>
            @if( $billingLogoDataUri )
            <div style="margin-top:8px;">
                <div style="font-size:12px; font-weight:bold; letter-spacing:1px; color:#555; margin-bottom:4px;">{{ __( 'POWERED BY:' ) }}</div>
                <img src="{{ $billingLogoDataUri }}"
                     alt="{{ ns()->option->get( 'ns_store_name' ) }}"
                     style="max-width:235px; max-height:126px; object-fit:contain;">
            </div>
            @endif
        </div>

        {{-- Right: Document Info --}}
        <div style="width:40%; text-align:right;">
            <div style="font-size:15px; font-weight:bold; font-style:italic; margin-bottom:6px;">
                {{ __( 'ORDER INVOICE' ) }}
            </div>
            <table style="width:100%; font-size:11px; border-collapse:collapse;">
                <tr>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ __( 'Document No.' ) }}</td>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ $order->code }}</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ __( 'Document Date' ) }}</td>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ \Carbon\Carbon::parse( $order->created_at )->format( 'm/d/Y' ) }}</td>
                </tr>
                @if( ! empty( $order->reference_no ) )
                <tr>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ __( 'Reference No.' ) }}</td>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ $order->reference_no }}</td>
                </tr>
                @endif
                @if( ! empty( $order->plate_no ) )
                <tr>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ __( 'Plate No.' ) }}</td>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ $order->plate_no }}</td>
                </tr>
                @endif
                <tr>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ __( 'Advisor' ) }}</td>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ $order->user ? ( trim( ( $order->user->first_name ?? '' ) . ' ' . ( $order->user->last_name ?? '' ) ) ?: $order->user->username ) : '—' }}</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ __( 'Customer No.' ) }}</td>
                    <td style="text-align:left; padding:2px 4px; border:1px solid #ccc;">{{ $order->customer_mobile ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{--
        Vehicle Info block — only renders if at least one vehicle
        field was actually filled in on the POS screen. Orders created
        before this feature existed (or without a vehicle) simply skip it.
    --}}
    @if( ! empty( $order->vehicle_make ) || ! empty( $order->vehicle_model ) || ! empty( $order->vehicle_chassis_no ) || ! empty( $order->plate_no ) )
    <div style="margin-bottom:8px;">
        <table style="width:100%; font-size:11px; border-collapse:collapse;">
            <tr>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold; width:16%;">{{ __( 'Year/Make/Model' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;" colspan="5">
                    {{ trim( ( $order->vehicle_year ?? '' ) . ' ' . ( $order->vehicle_make ?? '' ) . ' ' . ( $order->vehicle_model ?? '' ) ) ?: '—' }}
                </td>
            </tr>
            <tr>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Model No.' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;">{{ $order->vehicle_model_no ?? '—' }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Prod. Date' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;">{{ $order->prod_date ?? '—' }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Mileage' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;">{{ $order->current_mileage ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Chassis No.' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;" colspan="5">{{ $order->vehicle_chassis_no ?? '—' }}</td>
            </tr>
        </table>
    </div>
    @endif

    {{--
        Stock / Terms / Dealer / Representative — only shown if
        at least one of these was filled in.
    --}}
    @php
        $customerName = $order->customer
            ? trim( ( $order->customer->first_name ?? '' ) . ' ' . ( $order->customer->last_name ?? '' ) )
            : null;
    @endphp
    @if( ! empty( $customerName ) || ! empty( $order->stock_no ) || ! empty( $order->terms ) || ! empty( $order->customer_telephone ) || ! empty( $order->customer_fax ) )
    <div style="margin-bottom:8px;">
        <table style="width:100%; font-size:11px; border-collapse:collapse;">
            <tr>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold; width:16%;">{{ __( 'Customer Name' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc; width:34%;">{{ $customerName ?: '—' }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold; width:16%;">{{ __( 'Stock No.' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc; width:34%;">{{ $order->stock_no ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Terms' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;">{{ $order->terms ?? '—' }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Telephone' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;">{{ $order->customer_telephone ?? '—' }}</td>
            </tr>
            @if( ! empty( $order->customer_fax ) )
            <tr>
                <td style="padding:2px 4px; border:1px solid #ccc; font-weight:bold;">{{ __( 'Fax' ) }}</td>
                <td style="padding:2px 4px; border:1px solid #ccc;" colspan="3">{{ $order->customer_fax }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif
</div>