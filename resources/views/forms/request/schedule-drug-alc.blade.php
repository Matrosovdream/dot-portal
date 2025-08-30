

<div id="tab_registration">

  <div class="row mb-6">
    <x-select 
      inputName="fields[test_type]"
      label="Test type"
      inputId="test-type"
      :options="$formRefs['test_type']['options']"
      value="{{ $values['test_type'] ?? '' }}"
      :multiple=false
      :required=true
      template="inline"
    />
  </div>
  
  <div class="row mb-6">
    <x-select 
      inputName="fields[drivers]"
      label="Choose Drivers"
      inputId="drivers"
      :options="$formRefs['drivers']['options']"
      value="{{ $values['drivers'] ?? '' }}"
      :multiple=true
      :required=true
      template="inline"
    />
  </div>

  <div class="row mb-6">

    <x-select 
        inputName="fields[business_address_state_id]"
        label="Select a State"
        :options="$formRefs['country_state']['options']"
        value="{{ $values['business_address_state_id'] ?? '' }}"
        :multiple=false
        :required=true
        template="inline"
    />

  </div>

  <div class="row mb-6">
    <label class="col-lg-4 col-form-label fw-semibold fs-6">City</label>
    <div class="col-lg-4 fv-row">
      <input 
          type="text" 
          class="form-control form-control-lg form-control-solid" 
          placeholder="City"
          name="fields[business_address_city]" 
          value="{{ $values['business_address_city'] ?? '' }}"
          />
    </div>
  </div>

  <div class="row mb-6">
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Zip Code</label>
    <div class="col-lg-4 fv-row">
      <input 
          type="text" 
          class="form-control form-control-lg form-control-solid" 
          placeholder="Zip Code"
          name="fields[business_address_zip]" 
          value="{{ $values['business_address_zip'] ?? '' }}"
          />
    </div>
  </div>

</div>  



<script>

</script>