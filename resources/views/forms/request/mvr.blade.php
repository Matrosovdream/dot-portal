<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[driver_id]"
        label="Select Driver"
        :options="[
            ['value' => '0-2 Units', 'title' => '0-2 Units'],
            ['value' => '3-5 Units', 'title' => '3-5 Units'],
            ['value' => '6-20 Units', 'title' => '6-20 Units'],
            ['value' => '21-100 Units', 'title' => '21-100 Units']
        ]"
        value="3-5 Units"
        :multiple=false
        :required=true
    />
</div>


<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[mvr_type]"
        label="Select MVR Type"
        :options="[
            ['value' => '0-2 Units', 'title' => '0-2 Units'],
            ['value' => '3-5 Units', 'title' => '3-5 Units'],
            ['value' => '6-20 Units', 'title' => '6-20 Units'],
            ['value' => '21-100 Units', 'title' => '21-100 Units']
        ]"
        value="3-5 Units"
        :multiple=false
        :required=true
    />
</div>


<div class="col-md-6 mb-3">
    <x-file-uploader
        inputName="fields[auth_document]"
        label="Upload signed MVR authorization"
        value=""
        :multiple=false
        :required=true
    />
</div>
