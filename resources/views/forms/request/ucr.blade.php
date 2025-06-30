<div class="row mb-6">
    <x-select 
                inputName="fields[range_units]"
                label="Range of Units"
                :options="$formRefs['range_units']['options']"
                value="{{ $values['range_units'] ?? '' }}"
                :multiple=false
                :required=true
                template="inline"
            />
</div>           

<div class="row mb-6">
    <x-select 
                inputName="fields[permit_year]"
                label="Permit Year"
                :options="$formRefs['permit_year']['options']"
                value="{{ $values['permit_year'] ?? '' }}"
                :multiple=false
                :required=true
                template="inline"
            />
</div>