<?php
namespace App\References;

class ServiceReferences {

    public function getPredefinedForms() {

        return [
            1 => [
                'id' => 1,
                'name' => 'MCS-150',
                'path' => 'forms.request.mcs150',
                'fields' => []
            ],
            2 => [
                'id' => 2,
                'name' => 'UCR',
                'path' => 'forms.request.ucr',
                'fields' => [
                    'range_of_units' => ['title' => 'Range of units', 'type' => 'text', 'required' => true],
                    'date_of_birth' => ['title' => 'Date of birth', 'type' => 'date', 'required' => true],
                    'permit_year' => ['title' => 'Permit year', 'type' => 'text', 'required' => true],
                ]
            ],
            3 => [
                'id' => 3,
                'name' => 'Road Taxes',
                'path' => 'forms.request.road-taxes',
                'fields' => []
            ],
            4 => [
                'id' => 4,
                'name' => 'IFTA',
                'path' => 'forms.request.ifta',
                'fields' => []
            ],
            5 => [
                'id' => 5,
                'name' => 'IRP',
                'path' => 'forms.request.irp',
                'fields' => []
            ],
        ];
        
    }

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

    public function getFormFields($form_id=null) {

        if( empty($form_id) ) {
            return [];
        }

        $forms = $this->getPredefinedForms();

        if( isset($forms[$form_id]) ) {
            return $forms[$form_id]['fields'] ?? [];
        }

        return [];

    }

}