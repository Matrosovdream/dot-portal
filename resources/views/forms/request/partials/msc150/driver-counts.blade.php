<div class="d-flex align-items-center justify-content-between mb-5">
    <h5 class="">Driver counts</h5>
    <label class="form-check form-switch mb-0">
      <input 
        id="driverCountsToggle"
        name="fields[driver_counts_toggle]"
        value="1"
        type="checkbox" 
        class="form-check-input section-toggle" 
        data-section="driver-counts" 
        @if( $values['driver_counts_toggle'] == 1 ) checked @endif
        >
    </label>
  </div>
  
    <div class="toggle-section" id="section-driver-counts">

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Total drivers</label>
            <div class="col-lg-4 fv-row">
                <input 
                    type="number" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="Total drivers"
                    name="fields[drivers_number]"
                    value="{{ $values['drivers_number'] ?? '' }}">
            </div>
        </div>

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">CDL License Holders</label>
            <div class="col-lg-4 fv-row">
                <input 
                    type="number" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="CDL License Holders"
                    name="fields[cdl_license_holders]"
                    value="{{ $values['cdl_license_holders'] ?? '' }}">
            </div>
        </div>

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Interstate Drivers</label>
            <div class="col-lg-4 fv-row">
                <input 
                    type="number" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="Interstate Drivers"
                    name="fields[interstate_drivers]"
                    value="{{ $values['interstate_drivers'] ?? '' }}">
            </div>
        </div>

        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Intrastate Drivers</label>
            <div class="col-lg-4 fv-row">       
                <input 
                    type="number" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="Intrastate Drivers"
                    name="fields[intrastate_drivers]"
                    value="{{ $values['intrastate_drivers'] ?? '' }}">

            </div>
        </div>

    </div>
