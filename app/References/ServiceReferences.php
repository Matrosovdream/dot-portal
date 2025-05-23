<?php
namespace App\References;

class ServiceReferences {

    public static function serviceFields() {

        return [
            [
                'title' => "When do you arrive at the country destination?",
                'slug' => "arrival_date",
                'entity' => "service",
                'type' => "date",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'calendar.svg',
                'classes' => 'datepicker-min-today min-5-alert'
            ],
            [
                'title' => "Which airport do you arrive?",
                'slug' => "destination_airport",
                'entity' => "service",
                'type' => "reference",
                'placeholder' => "",
                'tooltip' => "If your arrival point isn't listed, we can't process your request.",
                'description' => "",
                'default_value' => "",
                'reference_code' => "airport",
                'default' => true,
                'section' => "trip",
                'icon' => 'location-2.svg',
            ],
            [
                'title' => "Your full name",
                'slug' => "full_name",
                'entity' => "service",
                'type' => "text",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'c_user.svg',
            ],
            [
                'title' => "Phone number",
                'slug' => "phone_number",
                'entity' => "service",
                'type' => "phone",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'c_call.svg',
            ],
            [
                'title' => "Email address",
                'slug' => "email",
                'entity' => "service",
                'type' => "email",
                'placeholder' => "",
                'tooltip' => "We use this to create your account and send you updates about your application.",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "trip",
                'icon' => 'c_mail.svg',
            ],
        ];

    }

    public function getPredefinedForms() {

        return [
            [
                'id' => 1,
                'name' => 'UCR'
            ],
            [
                'id' => 2,
                'name' => 'Road Taxes'
            ],
            [
                'id' => 3,
                'name' => 'Annual report'
            ],
            [
                'id' => 4,
                'name' => 'IFTA'
            ],
            [
                'id' => 5,
                'name' => 'State permits'
            ],
            [
                'id' => 6,
                'name' => 'IRP Service'
            ],
            [
                'id' => 7,
                'name' => 'DOT Update'
            ],
            [
                'id' => 8,
                'name' => 'Fuel Tax Quarterly Filing'
            ],
            [
                'id' => 9,
                'name' => 'MVR'
            ],
            [
                'id' => 10,
                'name' => 'New Driver Setup'
            ],
            [
                'id' => 11,
                'name' => 'Add to Fleet'
            ],
            [
                'id' => 12,
                'name' => 'Equipment Inspection'
            ],
            [
                'id' => 13,
                'name' => 'Terminate Driver'
            ],
            [
                'id' => 14,
                'name' => 'Terminate Equipment'
            ],
        ];
        
    }

}