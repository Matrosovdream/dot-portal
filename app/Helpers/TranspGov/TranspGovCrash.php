<?php

namespace App\Helpers\TranspGov;

class TranspGovCrash extends TranspGovAbstract {

    protected $api;
    protected $apiRoute = 'crash.history';

    public function mapItemsForModel(array $items) {
        
        $itemsNew = [];
        foreach ($items as $item) {
            $itemNew = [
                'unit_vin' => $item['vehicle_identification_number'] ?? null,   
                'dot_number' => $item['dot_number'] ?? null,
                'report_date' => $this->parseDate( $item['report_date'] ?? '' ),
                'report_number' => $item['report_number'] ?? null,
                'report_state' => $item['report_state'] ?? null,
                'total_injuries' => $item['injuries'] ?? null,
                'total_fatalities' => $item['fatalities'] ?? null,
                'api_data' => $item
            ];
            
            $itemsNew[] = $itemNew;
        }

        return $itemsNew;

    }

}