<?php
namespace App\References;

class ServiceReferences {

    public static function serviceFields() {

        return [
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
            [
                'title' => "Document",
                'slug' => "document",
                'entity' => "service",
                'type' => "file",
                'placeholder' => "",
                'tooltip' => "",
                'description' => "",
                'default_value' => "",
                'reference_code' => "",
                'default' => true,
                'section' => "",
                'icon' => '',
            ],
        ];

    }

    public function getPredefinedForms() {

        return [
            1 => [
                'id' => 1,
                'name' => 'UCR',
                'path' => 'forms.request.ucr',
                'fields' => [
                    'range_of_units' => ['title' => 'Range of units', 'type' => 'text', 'required' => true],
                    'date_of_birth' => ['title' => 'Date of birth', 'type' => 'date', 'required' => true],
                    'permit_year' => ['title' => 'Permit year', 'type' => 'text', 'required' => true],
                ]
            ],
            2 => [
                'id'=> 2,
                'name' => 'Road Taxes'
            ],
            3 => [
                'id'=> 3,
                'name' => 'Annual report'
            ],
            4 => [
                'id'=> 4,
                'name' => 'IFTA'
            ],
            5 => [
                'id'=> 5,
                'name' => 'State permits'
            ],
            6 => [
                'id'=> 6,
                'name' => 'IRP Service'
            ],
            7 => [
                'id'=> 7,
                'name' => 'DOT Update'
            ],
            8 => [
                'id'=> 8,
                'name' => 'Fuel Tax Quarterly Filing'
            ],
            9 => [
                'id'=> 9,
                'name' => 'MVR',
                'path' => 'forms.request.mvr',
                'fields' => [
                    'driver_id' => ['title' => 'Driver', 'type' => 'select', 'required' => true],
                    'mvr_type' => ['title' => 'MVR type', 'type' => 'select', 'required' => true],
                    'auth_document' => ['title' => 'Upload signed MVR authorization', 'type' => 'file', 'required' => true],
                ]
            ],
            10 => [
                'id'=> 10,
                'name' => 'New Driver Setup'
            ],
            11 => [
                'id'=> 11,
                'name' => 'Add to Fleet'
            ],
            12 => [
                'id'=> 12,
                'name' => 'Equipment Inspection'
            ],
            13 => [
                'id'=> 13,
                'name' => 'Terminate Driver'
            ],
            14 => [
                'id'=> 14,
                'name' => 'Terminate Equipment'
            ],
        ];
        
    }

}