<div class="d-flex align-items-center justify-content-between mb-5">
    <h5 class="">Operation Info</h5>
    <label class="form-check form-switch mb-0">
      <input 
        id="operationInfoToggle"
        name="fields[operation_info_toggle]"
        value="1"
        type="checkbox" 
        class="form-check-input section-toggle" 
        data-section="operation-info" 
        @if( $values['operation_info_toggle'] == 1 ) checked @endif
        >
    </label>
  </div>
  
  <div class="toggle-section" id="section-operation-info">

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Number of drivers</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="number" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Number of drivers"
            name="fields[drivers_number]"
            value="{{ $values['drivers_number'] ?? '' }}">
      </div>
    </div>

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Number of vehicles</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="number" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Number of vehicles"
            name="fields[vehicles_number]"
            value="{{ $values['vehicles_number'] ?? '' }}">
      </div>
    </div>

    <div class="row mb-6">
      
      <x-select 
          inputName="fields[operation_type]"
          label="Operation Type"
          :options="$formRefs['operation_type']['options']"
          value="{{ $values['operation_type'] ?? '' }}"
          :multiple=false
          :required=true
          template="inline"
      />
      
  </div>

    <div class="row mb-6">
      
        <x-select 
            inputName="fields[cargo_type]"
            label="Cargo Classifications"
            :options="$formRefs['cargo_type']['options']"
            value="{{ $values['cargo_type'] ?? '' }}"
            :multiple=true
            :required=true
            template="inline"
        />
        
    </div>

    <!-- Hazardous Materials Start -->
    @include('forms.request.partials.msc150.hazardous-materials')
    <!-- Hazardous Materials End -->

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Mileage</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="number" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Mileage"
            name="fields[mileage]"
            value="{{ $values['mileage'] ?? '' }}">
      </div>
    </div>

  </div>