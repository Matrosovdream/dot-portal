<h2 class="mb-6">General</h2>

<div class="row mb-6">
  <x-select 
    inputName="fields[driver]"
    label="Choose Driver"
    inputId="driver"
    :options="$formRefs['drivers']['options']"
    value="{{ $values['driver'] ?? '' }}"
    :multiple=false
    :required=true
    template="inline"
  />
</div>

<div class="row mb-6">
    <label class="col-lg-4 col-form-label fw-semibold fs-6">
      Primary Driver Email
    </label>
    <div class="col-lg-4 fv-row">
        <input 
            type="text" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Primary Driver Email"
            name="fields[primary_driver_email]"
            value="{{ $values['primary_driver_email'] ?? '' }}"
            />
    </div>
</div>

<div class="row mb-6">
    <label class="col-lg-4 col-form-label fw-semibold fs-6">
      Primary Driver Phone
    </label>
    <div class="col-lg-4 fv-row">
        <input 
            type="text" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Primary Driver Phone"
            name="fields[primary_driver_phone]"
            value="{{ $values['primary_driver_phone'] ?? '' }}"
            />
    </div>
</div>


