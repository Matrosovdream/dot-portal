

<div id="tab_registration">
  
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

</div>  



<script>

</script>