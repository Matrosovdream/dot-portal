<x-date
    inputName="date_of_birth"
    label="Date of Birth"
    :required=true
    :placeholder="__('Enter your date of birth')"
    :note="__('Please enter your date of birth in the format YYYY-MM-DD')"
    :description="__('This field is required for age verification')"
    :value="2025-01-01"
/>


<x-select 
    inputName="range_of_units"
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
