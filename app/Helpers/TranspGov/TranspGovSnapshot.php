<?php

namespace App\Helpers\TranspGov;

class TranspGovSnapshot extends TranspGovAbstract {

    protected $api;
    protected $apiRoute = 'company.snapshot';

    public function mapItemsForModel(array $items) {

        $itemsNew = [];
        foreach ($items as $item) {
            $itemNew = [
                'dot_number' => $item['dot_number'] ?? null,
                'mc_number' => $item['mc_number'] ?? null,
                'legal_name' => $item['legal_name'] ?? null,
                'dba_name' => $item['dba_name'] ?? null,
                'entity_type' => $item['entity_type'] ?? null,
                'physical_address' => $this->makePhysicalAddress($item),
                'mailing_address' => $this->makeMailingAddress($item),
                'latest_update' => $this->parseDate( $item['mcs150_date'] ?? null ),
                'api_data' => $item
            ];
            
            $itemsNew[] = $itemNew;
        }

        return $itemsNew;

    }

    protected function makePhysicalAddress(array $item): string {
        
        $elements = ['phy_street', 'phy_city', 'phy_country', 'phy_state', 'phy_zip', 'phy_cnty'];
        return $this->makeAddress($item, $elements);

    }

    protected function makeMailingAddress(array $item): string {
        
        $elements = ['carrier_mailing_street', 'carrier_mailing_city', 'carrier_mailing_country', 
                     'carrier_mailing_state', 'carrier_mailing_zip', 'carrier_mailing_cnty'];
        return $this->makeAddress($item, $elements);

    }

    protected function makeAddress(array $item, array $elements): string {
        
        $address = [];
        foreach ($elements as $element) {
            if (isset($item[$element]) && !empty($item[$element])) {
                $address[] = $item[$element];   
            }
        }

        return implode(', ', $address);

    }


}