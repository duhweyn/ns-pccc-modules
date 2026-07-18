<style>
    #vehicle-info-toggle {
        position: fixed; bottom: 10px; right: 10px; z-index: 9998;
        background: #1f2937; color: white; border: none; border-radius: 6px;
        padding: 10px 16px; font-size: 13px; font-weight: bold; cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    #vehicle-info-panel {
        position: fixed; bottom: 55px; right: 10px; z-index: 9999;
        background: white; border: 1px solid #d1d5db; border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        width: 340px; max-height: 70vh; overflow-y: auto;
        padding: 14px; display: none; font-size: 12px;
    }
    #vehicle-info-panel h4 {
        margin: 10px 0 6px 0; font-size: 12px; font-weight: bold;
        color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;
    }
    #vehicle-info-panel h4:first-child { margin-top: 0; }
    #vehicle-info-panel label {
        display: block; font-size: 11px; color: #6b7280; margin-top: 6px; margin-bottom: 2px;
    }
    #vehicle-info-panel input {
        width: 100%; padding: 5px 7px; border: 1px solid #d1d5db; border-radius: 4px;
        font-size: 12px; box-sizing: border-box;
    }
</style>

<button id="vehicle-info-toggle" type="button">Vehicle Info</button>

<div id="vehicle-info-panel">
    <h4>Document</h4>
    <label>Reference No.</label>
    <input type="text" id="vif_reference_no">
    <label>Plate No.</label>
    <input type="text" id="vif_plate_no">

    <h4>Vehicle</h4>
    <label>Year</label>
    <input type="text" id="vif_vehicle_year">
    <label>Make</label>
    <input type="text" id="vif_vehicle_make">
    <label>Model</label>
    <input type="text" id="vif_vehicle_model">
    <label>Model No.</label>
    <input type="text" id="vif_vehicle_model_no">
    <label>Chassis No.</label>
    <input type="text" id="vif_vehicle_chassis_no">
    <label>Prod. Date</label>
    <input type="text" id="vif_prod_date">
    <label>Current Mileage</label>
    <input type="text" id="vif_current_mileage">

    <h4>Stock / Terms</h4>
    <label>Stock No.</label>
    <input type="text" id="vif_stock_no">
    <label>Terms</label>
    <input type="text" id="vif_terms">

    <h4>Customer Contact</h4>
    <label>Telephone</label>
    <input type="text" id="vif_customer_telephone">
    <label>Mobile (Customer No.)</label>
    <input type="text" id="vif_customer_mobile">
    <label>Fax</label>
    <input type="text" id="vif_customer_fax">
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('vehicle-info-toggle');
    const panel = document.getElementById('vehicle-info-panel');

    toggleBtn.addEventListener('click', () => {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });

    const fieldIds = [
        'reference_no', 'plate_no', 'vehicle_year', 'vehicle_make', 'vehicle_model',
        'vehicle_model_no', 'vehicle_chassis_no', 'prod_date',
        'current_mileage', 'stock_no', 'terms',
        'customer_telephone', 'customer_mobile', 'customer_fax'
    ];

    // Populate the order object right before it's sent to the backend
    window.nsHooks.addAction('ns-order-before-submit', 'hide-menus-vehicle-info', (order) => {
        fieldIds.forEach((field) => {
            const input = document.getElementById('vif_' + field);
            if (input && input.value !== '') {
                order[field] = input.value;
            }
        });
    });

    // Clear the form only after a successful sale, so a failed
    // submission doesn't wipe out what the cashier already typed
    window.nsHooks.addAction('ns-order-submit-successful', 'hide-menus-vehicle-info-reset', () => {
        fieldIds.forEach((field) => {
            const input = document.getElementById('vif_' + field);
            if (input) { input.value = ''; }
        });
        panel.style.display = 'none';
    });
});
</script>