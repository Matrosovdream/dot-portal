<?php

namespace App\Helpers\RequestForms\Predefined\Forms;

use App\Helpers\RequestForms\Predefined\AbstractForm;

class MscForm extends AbstractForm
{
    protected $formName = 'mcs150';
    protected $formTitle = 'MCS-150';
    protected $formFields = [
        'pin' => [
            'type' => 'text',
            'label' => 'PIN',
            'required' => true,
        ],
        'change_type' => [
            'type' => 'select',
            'label' => 'Change Type',
            'reference' => 'change_type',
            'required' => true,
        ],

        // Main info
        'drivers_number' => [
            'type' => 'number',
            'label' => 'Number of Drivers',
            'required' => false,
        ],
        'vehicles_number' => [
            'type' => 'number',
            'label' => 'Number of Vehicles',
            'required' => false,
        ],
        'main_info_toggle' => [
            'type' => 'checkbox',
            'label' => 'Main Information',
            //'default' => true,
            'section' => 'main-info',
        ],
        'ein' => [
            'type' => 'text',
            'label' => 'EIN',
            'required' => false,
        ],

        // Business address fields
        'business_address_toggle' => [
            'type' => 'checkbox',
            'label' => 'Business Address',
            //'default' => true,
            'section' => 'business-address',
        ],
        'business_address1' => [
            'type' => 'text',
            'label' => 'Business Address 1',
            'required' => false,
        ],
        'business_address2' => [
            'type' => 'text',
            'label' => 'Business Address 2',
            'required' => false,
        ],
        'business_address_city' => [
            'type' => 'text',
            'label' => 'Business Address City',
            'required' => false,
        ],
        'business_address_state_id' => [
            'type' => 'select',
            'label' => 'Business Address State',
            'reference' => 'country_state',
            'required' => false,
        ],
        'business_address_zip' => [
            'type' => 'text',
            'label' => 'Business Address Zip',
            'required' => false,
        ],

        // Mailing address fields
        'mailing_address_toggle' => [
            'type' => 'checkbox',
            'label' => 'Mailing Address',
            //'default' => true,
            'section' => 'mailing-address',
        ],
        'mailing_address1' => [
            'type' => 'text',
            'label' => 'Mailing Address 1',
            'required' => false,
        ],
        'mailing_address2' => [
            'type' => 'text',
            'label' => 'Mailing Address 2',
            'required' => false,
        ],
        'mailing_address_city' => [
            'type' => 'text',
            'label' => 'Mailing Address City',
            'required' => false,
        ],
        'mailing_address_state_id' => [
            'type' => 'select',
            'label' => 'Mailing Address State',
            'reference' => 'country_state',
            'required' => false,
        ],
        'mailing_address_zip' => [          
            'type' => 'text',
            'label' => 'Mailing Address Zip',
            'required' => false,
        ],

        // Contact information fields
        'contact_info_toggle' => [
            'type' => 'checkbox',
            'label' => 'Contact Information',
            //'default' => true,
            'section' => 'contact-info',
        ],
        'contact_name' => [
            'type' => 'text',
            'label' => 'Contact Name',
            'required' => false,
        ],
        'contact_phone' => [
            'type' => 'text',
            'label' => 'Contact Phone',
            'required' => false,
        ],
        'contact_email' => [
            'type' => 'email',
            'label' => 'Contact Email',
            'required' => false,
        ],

        // Operation information fields
        'operation_info_toggle' => [
            'type' => 'checkbox',
            'label' => 'Operation Information',
            //'default' => true,
            'section' => 'operation-info',
        ],
        'operation_type' => [
            'type' => 'select',
            'label' => 'Operation Type',
            'reference' => 'operation_type',
            'required' => false,
        ],
        'cargo_type' => [
            'type' => 'select',
            'label' => 'Cargo Type',
            'reference' => 'cargo_type',
            'required' => false,     
            'multiple' => true,
        ],
        'mileage' => [
            'type' => 'text',
            'label' => 'Mileage',
            'required' => false,
        ],
        
    ];

    protected $fieldDependencies = [
        'change_type' => [
            'keep' => [
                'show' => [
                    'pin',
                    'change_type',
                ]
            ],
            'change' => [
                'show' => [
                    'pin',
                    'change_type',
                    'ein',
                    'business_address1',
                    'business_address_city',
                    'business_address_state_id',
                    'business_address_zip',
                    'mailing_address1',
                    'mailing_address_city',
                    'mailing_address_state_id',
                    'mailing_address_zip',
                    'contact_name',
                    'contact_phone',
                    'contact_email',
                    'operation_type',
                    'cargo_type',
                    'mileage',
                ],
            ],
        ],
    ];

    public function validateFormData($requestData) {
        
        $errors = parent::validateFormData($requestData);

        if( $requestData['change_type'] === 'change' ) {
            
            $reqFields = [
                'pin',
                'ein',
                'business_address1',
                'business_address_city',
                'business_address_state_id',
                'business_address_zip',
                'mailing_address1',
                'mailing_address_city',
                'mailing_address_state_id',
                'mailing_address_zip',
                'contact_name',
                'contact_phone',
                'contact_email',
                'operation_type',
                'cargo_type',
                'mileage',
            ];

            foreach ($reqFields as $field) {
                if (empty($requestData[$field])) {
                    $errors[$field] = 'The ' . $this->formFields[$field]['label'] . ' field is required.';
                }
            }

        } 

        return $errors;

    }

    public function getReferences() {

        $refs = [
            'change_type' => [
                'type' => 'select',
                'label' => 'Change Type',
                'options' => [
                    ['value' => 'keep', 'title' => 'Keep same'],
                    ['value' => 'change', 'title' => 'Change information'],
                ],
            ],
            'operation_type' => [
                'type' => 'select',
                'label' => 'Operation Type',
                'options' => [
                    ['value' => 'interstate', 'title' => 'Interstate Carrier'],
                    ['value' => 'intrastate', 'title' => 'Intrastate Hazmat Carrier'],
                    ['value' => 'interstate_non_hazmat', 'title' => 'Intrastate Non Hazmat Carrier'],
                ],
            ],
            'operation_classif' => [
                'type' => 'select',
                'label' => 'Operation Classifications',
                'options' => [
                    [ 'value' => 'authorized_hire', 'title' => 'Authorized ForHire'],
                    [ 'value' => 'exempt_hire', 'title' => 'Exempt For Hire'],
                    [ 'value' => 'private_property', 'title' => 'Private Property'],
                    [ 'value' => 'private_motor_business', 'title' => 'Private Motor Carrier of Passengers (Business)'],
                    [ 'value' => 'private_motor_non_business', 'title' => 'Private Motor Carrier of Passengers (Non Business)'],
                ],
            ],
            'cargo_type' => [
                'type' => 'select',
                'label' => 'Cargo Classifications',
                'options' => [
                    [ 'value' => 'general_freight', 'title' => 'General Freight' ],
                    [ 'value' => 'household_goods', 'title' => 'Household Goods' ],
                    [ 'value' => 'metal_sheets_coils_rolls', 'title' => 'Metal: Sheets, Coils, Rolls' ],
                    [ 'value' => 'motor_vehicles', 'title' => 'Motor Vehicles' ],
                    [ 'value' => 'drive_away_tow_away', 'title' => 'Drive‑Away/Tow‑Away' ],
                    [ 'value' => 'logs_poles_beams_lumber', 'title' => 'Logs, Poles, Beams, Lumber' ],
                    [ 'value' => 'building_materials', 'title' => 'Building Materials' ],
                    [ 'value' => 'mobile_homes', 'title' => 'Mobile Homes' ],
                    [ 'value' => 'machinery_large_objects', 'title' => 'Machinery, Large Objects' ],
                    [ 'value' => 'fresh_produce', 'title' => 'Fresh Produce' ],
                    [ 'value' => 'liquids_gases', 'title' => 'Liquids/Gases' ],
                    [ 'value' => 'intermodal_container', 'title' => 'Intermodal Container' ],
                    [ 'value' => 'passengers', 'title' => 'Passengers' ],
                    [ 'value' => 'oil_field_equipment', 'title' => 'Oil Field Equipment' ],
                    [ 'value' => 'livestock', 'title' => 'Livestock' ],
                    [ 'value' => 'grain_feed_hay', 'title' => 'Grain, Feed, Hay' ],
                    [ 'value' => 'coal_coke', 'title' => 'Coal/Coke' ],
                    [ 'value' => 'meat', 'title' => 'Meat' ],
                    [ 'value' => 'garbage_refuse_trash', 'title' => 'Garbage/Refuse/Trash' ],
                    [ 'value' => 'us_mail', 'title' => 'U.S. Mail' ],
                    [ 'value' => 'chemicals', 'title' => 'Chemicals' ],
                    [ 'value' => 'commodities_dry_bulk', 'title' => 'Commodities Dry Bulk' ],
                    [ 'value' => 'refrigerated_food', 'title' => 'Refrigerated Food' ],
                    [ 'value' => 'beverages', 'title' => 'Beverages' ],
                    [ 'value' => 'paper_products', 'title' => 'Paper Products' ],
                    [ 'value' => 'utility', 'title' => 'Utility' ],
                    [ 'value' => 'farm_supplies', 'title' => 'Farm Supplies' ],
                    [ 'value' => 'construction', 'title' => 'Construction' ],
                    [ 'value' => 'water_well', 'title' => 'Water Well' ],
                    [ 'value' => 'other', 'title' => 'Other' ],
                ]
            ],
            'hazardous_materials' => [
                'type' => 'select',
                'label' => 'Hazardous Materials',
                'options' => [
                    [ 'value' => 'explosives_1_1', 'title' => 'Div 1.1 Explosives (mass explosion hazard)' ],
                    [ 'value' => 'explosives_1_2', 'title' => 'Div 1.2 Explosives (projection hazard)' ],
                    [ 'value' => 'explosives_1_3', 'title' => 'Div 1.3 Explosives (predominantly fire hazard)' ],
                    [ 'value' => 'explosives_1_4', 'title' => 'Div 1.4 Explosives (no significant blast hazard)' ],
                    [ 'value' => 'explosives_1_5', 'title' => 'Div 1.5 Very insensitive explosive blasting agents' ],
                    [ 'value' => 'explosives_1_6', 'title' => 'Div 1.6 Extremely insensitive detonating substances' ],
                    [ 'value' => 'flammable_gas_2_1', 'title' => 'Div 2.1 Flammable Gas (including LPG, Methane)' ],
                    [ 'value' => 'compressed_gas_2_2', 'title' => 'Div 2.2 Non‑flammable compressed gas' ],
                    [ 'value' => 'poison_gas_2_3', 'title' => 'Div 2.3 A‑D Poison gases (PIH Zone A–D)' ],
                    [ 'value' => 'flammable_liquid_3', 'title' => 'Class 3 Flammable and combustible liquids' ],
                    [ 'value' => 'flammable_solid_4_1', 'title' => 'Class 4.1 Flammable solids' ],
                    [ 'value' => 'spont_combust_4_2', 'title' => 'Class 4.2 Spontaneously combustible material' ],
                    [ 'value' => 'dangerous_wet_4_3', 'title' => 'Class 4.3 Dangerous when wet material' ],
                    [ 'value' => 'oxidizer_5_1', 'title' => 'Class 5.1 Oxidizers' ],
                    [ 'value' => 'peroxide_5_2', 'title' => 'Class 5.2 Organic peroxides' ],
                    [ 'value' => 'poison_6_1', 'title' => 'Class 6.1 Poison liquids and solids (PIH Zones A–C)' ],
                    [ 'value' => 'infectious_6_2', 'title' => 'Class 6.2 Infectious substances' ],
                    [ 'value' => 'radioactive_7', 'title' => 'Class 7 Radioactive materials (including HRCQ)' ],
                    [ 'value' => 'corrosive_8', 'title' => 'Class 8 Corrosive materials (PIH Zones A–B)' ],
                    [ 'value' => 'misc_hazmat_9', 'title' => 'Class 9 Miscellaneous hazardous materials' ],
                    [ 'value' => 'elevated_temp', 'title' => 'Elevated Temperature Material' ],
                    [ 'value' => 'infectious_waste', 'title' => 'Infectious Waste' ],
                    [ 'value' => 'marine_pollutant', 'title' => 'Marine Pollutants' ],
                    [ 'value' => 'haz_substance_rq', 'title' => 'Hazardous Substances (RQ)' ],
                    [ 'value' => 'haz_waste', 'title' => 'Hazardous Waste' ],
                    [ 'value' => 'orm', 'title' => 'ORM' ],
                ]
            ],
            'vehicle_categories' => [
                'type' => 'select',
                'label' => 'Vehicle Categories',
                'options' => [
                    [ 'value' => 'straight_trucks', 'title' => 'Straight Trucks' ],
                    [ 'value' => 'truck_tractors', 'title' => 'Truck Tractors' ],
                    [ 'value' => 'trailers', 'title' => 'Trailers' ],
                    [ 'value' => 'hazmat_tank_trucks', 'title' => 'Hazmat Cargo Tank Trucks' ],
                    [ 'value' => 'hazmat_tank_trailers', 'title' => 'Hazmat Cargo Tank Trailers' ],
                    [ 'value' => 'motor_coaches', 'title' => 'Motor Coaches' ],
                    [ 'value' => 'school_buses', 'title' => 'School Buses (≤8, 9–15, ≥16 seats)' ],
                    [ 'value' => 'buses_16_plus', 'title' => 'Buses (≥16 seats)' ],
                    [ 'value' => 'vans', 'title' => 'Vans (≤8, 9–15 seats)' ],
                    [ 'value' => 'limousines', 'title' => 'Limousines (≤8, 9–15 seats)' ],
                ]
            ],
            'country_state' => [
                'type' => 'select',
                'label' => 'State',
                'options' => $this->getCountryStates(),
            ],
        ];

        return $this->prepareReferences( $refs );

    }

    public function prefillValues( $user_id = null ) {

        $dataSet = [];

        if( !$user_id ) {
            $user_id = auth()->id();
        }

        $companyRepo = app()->make('App\Repositories\User\UserCompanyRepo');
        $userRepo = app()->make('App\Repositories\User\UserRepo');

        $company = $companyRepo->getByUserID( $user_id );
        $user = $userRepo->getByID( $user_id );

        // Main Company Information
        $companyInfo = [
            'contact_name' => $company['name'] ?? '',
            'contact_phone' => $company['phone'] ?? '',
            'contact_email' => $user['email'] ?? '',
        ];

        // Business Address
        $address = $company['addresses']['business'] ?? [];
        $businessAddress = [
            'business_address1' => $address['address1'] ?? '',
            'business_address2' => $address['address2'] ?? '',
            'business_address_city' => $address['city'] ?? '',      
            'business_address_state_id' => $address['state_id'] ?? '',
            'business_address_zip' => $address['zip'] ?? '',
        ];

        // Mailing Address
        $address = $company['addresses']['mailing'] ?? [];
        $mailingAddress = [
            'mailing_address1' => $address['address1'] ?? '',
            'mailing_address2' => $address['address2'] ?? '',
            'mailing_address_city' => $address['city'] ?? '',
            'mailing_address_state_id' => $address['state_id'] ?? '',       
            'mailing_address_zip' => $address['zip'] ?? '',
        ];


        $dataSet = array_merge($companyInfo, $businessAddress, $mailingAddress);

        return $dataSet;

    }


}