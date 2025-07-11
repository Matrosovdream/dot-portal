<!-- Main Info -->
<div class="d-flex align-items-center justify-content-between mb-5">
    <h5>Main Info</h5>
    <label class="form-check form-switch mb-0">
      <input 
        name="fields[main_info_toggle]"
        type="checkbox" 
        class="form-check-input section-toggle" 
        data-section="main-info" 
        id="mainInfoToggle" 
        value="1"
        @if( $values['main_info_toggle'] == 1 ) checked @endif
        >
    </label>
  </div>

  <div class="toggle-section" id="section-main-info">
  
    <!-- EIN -->
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">EIN</label>
        <div class="col-lg-4 fv-row">
            <input 
                type="text" 
                class="form-control form-control-lg form-control-solid" 
                placeholder="EIN"
                name="fields[ein]"
                value="{{ $values['ein'] ?? '' }}"
                />
        </div>
    </div>

  </div> 
  <!-- End Main Info Section -->