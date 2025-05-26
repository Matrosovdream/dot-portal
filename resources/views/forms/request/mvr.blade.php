<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[driver_id]"
        label="Select Driver"
        :options="$references['companyDrivers'] ?? []"
        value=""
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
        value="3-5 Units"
        :multiple=false
        :required=true
    />
</div>


<div class="col-md-6 mb-3">
    <x-file-uploader
        inputName="fields[auth_document]"
        title="Upload MVR Authorization Document"
        label="Upload signed MVR authorization"
        value=""
        :multiple=false
        :required=true
    />
</div>
