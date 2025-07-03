<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ServiceField;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $fields = [
            ['field_id' => 1, 'order' => 1],
            ['field_id' => 2, 'order' => 2],
            ['field_id' => 3, 'order' => 3],
            ['field_id'=> 4, 'order'=> 4],
        ];
        
        $services = [
            [
                'name' => 'MCS-150 Update', 
                'slug' => 'msc-150-update', 
                'description' => '
                The MCS-150 Update is a mandatory form submitted to the FMCSA to update or verify your company’s USDOT information. It must be filed every two years or within 30 days of any changes. Failure to update can result in deactivation and fines.
                ', 
                'is_paid' => true,
                'price' => 50, 
                'status_id' => 1, 
                'group_id' => 1,
                'form_type' => 'predefined',
                'form_id' => 1,
            ],
            [
                'name' => 'UCR Renewal', 
                'slug' => 'ucr-renewel', 
                'description' => '
                UCR Renewal is the annual process where commercial motor carriers, freight forwarders, brokers, and leasing companies operating in the U.S. register and pay fees under the Unified Carrier Registration program. It ensures compliance with federal and state regulations and must be completed by December 31 each year for the upcoming calendar year.
                ', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 1,
                'form_type'=> 'predefined',
                'form_id' => 2, // Assuming 1 is the ID for UCR form
            ],
            [
                'name' => 'Road Taxes', 
                'slug' => 'road-taxes', 
                'description' => '
                Road tax is a mandatory fee imposed by the government on vehicle owners for using public roads. It helps fund road maintenance, infrastructure, and transportation services. The amount varies based on factors like vehicle type, engine size, emissions, and region.', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1,
                'group_id' => 1,
                'form_type' => 'predefined',
                'form_id' => 3, 
            ],
            [
                'name' => 'IFTA', 
                'slug' => 'ifta', 
                'description' => '
                The IFTA form is used by motor carriers and fleet operators to report fuel usage and calculate taxes owed across multiple jurisdictions (U.S. states and Canadian provinces). Under IFTA, carriers are required to file quarterly fuel tax returns that report the amount of fuel consumed, miles traveled, and fuel tax liability for each jurisdiction. This simplifies the fuel tax process by allowing carriers to file one return for all jurisdictions instead of filing separate reports with each state or province. The goal is to ensure that the appropriate fuel tax is paid to the correct jurisdictions.
                ', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 1,
                'form_type' => 'predefined',
                'form_id' => 4, 
            ],
            [
                'name' => 'IRP', 
                'slug' => 'irp', 
                'description' => '
                It allows for the apportionment of registration fees based on the distance traveled in each jurisdiction, enabling legal interstate and international operation for carriers.', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 1,
                'form_type' => 'predefined',
                'form_id' => 5, 
            ],
            [
                'name' => 'Clearing House New Driver / Company Registration', 
                'slug' => 'ch-new-driver', 
                'description' => '
The Clearing House New Driver/Company Registration form is used to enroll new drivers or companies into the U.S. Department of Transportation’s (DOT) Drug and Alcohol Clearinghouse. For drivers, it registers their personal and professional information to ensure compliance with federal drug and alcohol testing regulations. For companies, the form registers them as employers or carriers who are required to query the Clearinghouse for driver safety and compliance records. This registration is essential for verifying that drivers meet federal standards and maintaining a compliant workforce.', 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 2,
                'form_type' => 'predefined',
                'form_id' => 6, 
            ],
            [
                'name' => 'Clearing House Query Checks', 
                'slug' => 'ch-query-checks', 
                'description' => "
                Clearing House Query Check Form
The Clearing House Query Check form is used by employers and carriers to verify the safety and compliance history of drivers under the U.S. Department of Transportation's (DOT) Drug and Alcohol Clearinghouse. It ensures that drivers do not have unresolved violations or disqualifications related to drug and alcohol testing, as required by federal regulations. The form facilitates the process of confirming a driver’s eligibility for employment or continued operation.", 
                'is_paid' => false,
                'price' => 0, 
                'status_id' => 1, 
                'group_id' => 2,
                'form_type' => 'predefined',
                'form_id' => 7, 
            ],

        ];
        
        foreach ($services as $service) {
            $serviceData = Service::firstOrCreate($service);

            if( $service['form_type'] == 'custom' ) {

                // Add fields
                foreach ($fields as $field) {
                    ServiceField::firstOrCreate([
                        'service_id' => $serviceData->id,
                        'field_id' => $field['field_id'],
                        'order' => $field['order'],
                    ]);
                }

            }
            
        }

    }
}
