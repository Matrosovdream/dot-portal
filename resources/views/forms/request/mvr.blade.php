<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[driver_id]"
        label="Select Driver"
        :options="$references['companyDrivers'] ?? []"
        value="{{ $values['driver_id'] ?? '' }}"
        :multiple=false
        :required=true
    />
</div>


<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[mvr_type]"
        label="Select MVR Type"
        :options="[
            ['value' => 'A La Carte', 'title' => 'A La Carte'],
            ['value' => 'Annual', 'title' => 'Annual'],
        ]"
        value="{{ $values['mvr_type'] ?? '' }}"
        :multiple=false
        :required=true
    />
</div>


<div class="col-md-6 mb-3">
    <x-file-uploader
        inputName="fields[auth_document]"
        title="Upload MVR Authorization Document"
        label="Upload signed MVR authorization"
        value="{{ $values['auth_document'] ?? '' }}"
        :multiple=false
        :required=true
    />
</div>
