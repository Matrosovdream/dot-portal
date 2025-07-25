<!-- Vehicles info -->
<div class="d-flex align-items-center justify-content-between mb-5">
    <h5>Vehicles</h5>
    <label class="form-check form-switch mb-0">
        <input 
        name="fields[vehicle_info_toggle]"
        type="checkbox" 
        value="1"
        class="form-check-input section-toggle" 
        data-section="vehicle-info" 
        id="vehicleInfoToggle" 
        @if( 
            isset($values['vehicle_info_toggle']) &&
            $values['vehicle_info_toggle'] == 1 
            ) checked @endif
        >
    </label>
</div>

<div class="toggle-section" id="section-vehicle-info">

    <?php
    $vehicle_groups = [
        'truck_and_trailers' => [
            'title' => 'Truck and Trailers',
            'options' => [
                'Straight Trucks',
                'Truck Tractors',
                'Trailers',
                'Hazmat Cargo Tank Trucks',
                'Hazmat Cargo Tank Trailers',
            ],
        ],
        'passenger_vehicles' => [
            'title' => 'Passenger Vehicles',
            'options' => [
                'Motor-Coach',
                'School Bus (8 or less Passengers)',
                'School Bus (9-15 Passengers)',
                'School Bus (16+ Passengers)',
                'Bus (16+)',
                'Van (8 or less Passengers)',
                'Van (9-15 Passengers)',
                'Limousine (8 or less Passengers)',
                'Limousine (9-15 Passengers)',
            ],
        ], 
    ];
?>



<div class="separator my-6"></div>
<h3 class="fw-bold mb-6">Vehicles</h3>

<?php foreach ($vehicle_groups as $group_key => $group): ?>
    <div class="mb-8">
        <label class="d-block1 fw-semibold fs-5 mb-3"><?= $group['title'] ?>:</label>

        <div class="row">
            <?php foreach ($group['options'] as $option):
                $option_key = md5($option); ?>
                <div class="col-md-6 mb-6">
                    <div class="form-check form-check-custom form-check-solid mb-3">
                        <input class="form-check-input vehicle-toggle" type="checkbox"
                               id="toggle-<?= $option_key ?>"
                               data-target="<?= $option_key ?>">
                        <label class="form-check-label" for="toggle-<?= $option_key ?>">
                            <?= $option ?>
                        </label>
                    </div>

                    <div class="row1 gx-5 gy-3 vehicle-section" id="section-<?= $option_key ?>" style="display: none;">
                        <div class="col-md-4 fv-row">
                            <label class="form-label">Owned</label>
                            <input type="number" name="item_meta[<?= $option_key ?>][owned]"
                                   class="form-control form-control-solid"
                                   placeholder="0" min="0">
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="form-label">Term Leased</label>
                            <input type="number" name="item_meta[<?= $option_key ?>][term]"
                                   class="form-control form-control-solid"
                                   placeholder="0" min="0">
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="form-label">Trip Leased</label>
                            <input type="number" name="item_meta[<?= $option_key ?>][trip]"
                                   class="form-control form-control-solid"
                                   placeholder="0" min="0">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.vehicle-toggle').forEach(function (checkbox) {
            const sectionId = 'section-' + checkbox.dataset.target;
            const section = document.getElementById(sectionId);
    
            function toggleSection() {
                if (section) {
                    section.style.display = checkbox.checked ? 'flex' : 'none';
                }
            }
    
            toggleSection(); // run on load
            checkbox.addEventListener('change', toggleSection);
        });
    });
    </script>
    
    
    