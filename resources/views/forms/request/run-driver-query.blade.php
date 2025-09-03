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
  <x-select 
    inputName="fields[query_type]"
    label="Query Type"
    inputId="query_type"
    :options="$formRefs['query_type']['options']"
    value="{{ $values['query_type'] ?? '' }}"
    :multiple=false
    :required=true
    template="inline"
  />
</div>


