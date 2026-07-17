<table style="width:100%; border-collapse:collapse; font-size:11px;">
    <thead>
        <tr style="font-weight:bold;">
            <td style="padding:4px; border-bottom:2px solid #000;">{{ __( 'Item Description' ) }}</td>
            <td style="padding:4px; border-bottom:2px solid #000; text-align:center;">{{ __( 'Qty' ) }}</td>
            <td style="padding:4px; border-bottom:2px solid #000; text-align:center;">{{ __( 'U/M' ) }}</td>
            <td style="padding:4px; border-bottom:2px solid #000; text-align:right;">{{ __( 'Unit Price' ) }}</td>
            <td style="padding:4px; border-bottom:2px solid #000; text-align:right;">{{ __( 'Total' ) }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach( \App\Classes\Hook::filter( 'ns-receipt-products', $order->combinedProducts ) as $product )
        <tr>
            <td style="padding:4px; border-bottom:1px solid #ccc;">
                <?php echo \App\Classes\Hook::filter( 'ns-receipt-product-name', $product->name, $product );?>
            </td>
            <td style="padding:4px; border-bottom:1px solid #ccc; text-align:center;">{{ $product->quantity }}</td>
            <td style="padding:4px; border-bottom:1px solid #ccc; text-align:center;">
                @if ( ns()->option->get( 'ns_invoice_show_product_unit', 'yes' ) !== 'no' )
                    {{ $product->unit->name ?? '—' }}
                @endif
            </td>
            <td style="padding:4px; border-bottom:1px solid #ccc; text-align:right;">{{ ns()->currency->define( $product->unit_price ) }}</td>
            <td style="padding:4px; border-bottom:1px solid #ccc; text-align:right;">{{ ns()->currency->define( $product->total_price ) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
