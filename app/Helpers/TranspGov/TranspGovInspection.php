<?php

namespace App\Helpers\TranspGov;

class TranspGovInspection extends TranspGovAbstract {

    protected $api;
    protected $apiRoute = 'inspection.history';

    public function mapItemsForModel(array $items) {

        $itemsNew = [];
        foreach ($items as $item) {
            $itemNew = [
                'unit_vin' => $item['vehicle_identification_number'] ?? null,   
                'dot_number' => $item['dot_number'] ?? null,
                'unique_id' => $item['inspection_id'] ?? null,
                'report_date' => $this->parseDate( $item['insp_date'] ?? '' ),
                'report_number' => $item['report_number'] ?? null,
                'inspection_level' => $item['insp_level_id'] ?? null,
                'report_state' => $item['report_state'] ?? null,
                'api_data' => $item
            ];
            
            $itemsNew[] = $itemNew;
        }

        return $itemsNew;

    }

}