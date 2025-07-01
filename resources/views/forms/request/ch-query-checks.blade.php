<div class="row mb-6">
    <label class="col-lg-4 col-form-label fw-semibold fs-6">
        Amount of Queries
    </label>
    <div class="col-lg-4 fv-row">
        <input 
            type="number" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Amount of Queries"
            name="fields[query_amount]"
            value="{{ $values['query_amount'] ?? '' }}"
            />
    </div>
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