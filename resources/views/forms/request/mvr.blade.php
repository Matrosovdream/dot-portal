
<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[range_of_units]"
        label="Select the range of units"
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
    <x-date
        inputName="fields[date_of_birth]"
        label="Date of Birth"
        :required=true
        :placeholder="__('Enter your date of birth')"
        :note="__('Please enter your date of birth in the format YYYY-MM-DD')"
        :description="__('This field is required for age verification')"
        :value="2025-01-01"
    />
</div>

<div class="col-md-6 mb-3">
    <x-select 
        inputName="fields[permit_year]"
        label="Permit Year"
        :options="[
            ['value' => '2024', 'title' => '2024'],
            ['value' => '2025', 'title' => '2025'],
            ['value' => '2026', 'title' => '2026'],
            ['value' => '2027', 'title' => '2027']
        ]"
        value=""
        :multiple=false
        :required=true
    />
</div>
