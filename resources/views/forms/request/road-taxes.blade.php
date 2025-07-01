<div class="row mb-6">
    <x-select 
        inputName="fields[query_type]"
        label="Query type"
        :options="$formRefs['query_type']['options']"
        value="{{ $values['query_type'] ?? '' }}"
        :multiple=false
        :required=true
        template="inline"
    />
</div>           

<div class="row mb-6">
    <x-select 
        inputName="fields[vehicle_id]"
        label="Choose Vehicle"
        :options="$formRefs['vehicles']['options']"
        value="{{ $values['vehicle_id'] ?? '' }}"
        :multiple=false
        :required=true
        template="inline"
    />
</div>