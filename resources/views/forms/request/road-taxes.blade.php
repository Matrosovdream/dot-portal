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
        inputName="fields[vehicle]"
        label="Choose Vehicle"
        :options="$formRefs['vehicles']['options']"
        value="{{ $values['vehicles'] ?? '' }}"
        :multiple=false
        :required=true
        template="inline"
    />
</div>