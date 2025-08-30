<h2 class="mb-6">General</h2>

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