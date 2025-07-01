<!-- Change Type -->
<div class="row mb-6">

  <label class="col-lg-4 col-form-label fw-semibold fs-6">Change Type</label>
  <div class="col-lg-4 fv-row">
    <select name="fields[request_type]" id="requestType" class="form-select form-select-lg form-select-solid">
      <option value="reg">Registration</option>
      <option value="quarterly_filing">Quarterly Filing</option>
    </select>
  </div>

</div>

<div id="tab_registration">
  
  <div class="row mb-6">
    <x-select 
        inputName="fields[country_state]"
        label="State"
        :options="$formRefs['country_state']['options']"
        value="{{ $values['country_state'] ?? '' }}"
        :multiple=false
        :required=true
        template="inline"
    />
  </div>

  <div class="row mb-6">
    <x-select 
      inputName="fields[vehicles]"
      label="Choose Vehicle"
      inputId="vehicles1"
      :options="$formRefs['vehicles']['options']"
      value="{{ $values['vehicles'] ?? '' }}"
      :multiple=false
      :required=true
      template="inline"
    />
  </div>

</div>  

<div id="tab_quarterly_filing">

  <div class="row mb-6">
    <x-select 
        inputName="fields[filing_period]"
        label="Period"
        :options="$formRefs['filing_period']['options']"
        value="{{ $values['filing_period'] ?? '' }}"
        :multiple=false
        :required=true
        template="inline"
    />
  </div>

  <div class="row mb-6">
    <x-select 
      inputName="fields[vehicles]"
      inputId="vehicles2"
      label="Choose Vehicle"
      :options="$formRefs['vehicles']['options']"
      value="{{ $values['vehicles'] ?? '' }}"
      :multiple=false
      :required=true
      template="inline"
    />
  </div>

</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const requestType = document.getElementById('requestType');
    const tabRegistration = document.getElementById('tab_registration');
    const tabQuarterlyFiling = document.getElementById('tab_quarterly_filing');

    function toggleTabs() {
      if (requestType.value === 'reg') {
        tabRegistration.style.display = 'block';
        tabQuarterlyFiling.style.display = 'none';
      } else {
        tabRegistration.style.display = 'none';
        tabQuarterlyFiling.style.display = 'block';
      }
    }

    requestType.addEventListener('change', toggleTabs);
    toggleTabs(); // Initial call to set the correct tab on page load
  });
</script>