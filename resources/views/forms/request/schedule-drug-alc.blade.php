

<div id="tab_registration">
  
  <h2 class="mb-6">General</h2>

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

  <div class="separator mb-8"></div>

  <h2 class="mb-6">Address</h2>

  <div class="row mb-6">

    <x-select 
        inputName="fields[address_state_id]"
        label="Select a State"
        :options="$formRefs['country_state']['options']"
        value="{{ $values['address_state_id'] ?? '' }}"
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
          name="fields[address_city]" 
          value="{{ $values['address_city'] ?? '' }}"
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
          name="fields[address_zip]" 
          value="{{ $values['address_zip'] ?? '' }}"
          />
    </div>
  </div>

</div>  



<script>

</script>