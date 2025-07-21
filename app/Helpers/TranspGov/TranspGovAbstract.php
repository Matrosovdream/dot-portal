<?php

namespace App\Helpers\TranspGov;

class TranspGovAbstract {

    protected $api;
    protected $apiRoute = '';
    protected $paginateDefault = 10000; 
    public $mapWithModel = false;
    
    public function __construct() {
        
        $this->api = app('App\Mixins\Integrations\TranspGovAPI');

    }

    public function mapItemsForModel( array $items ) {
        return $items;
    }

    public function getByDot( int $dotNumber ) {

        $items = $this->getItemsByDot([$dotNumber], $this->paginateDefault);

        $items = $this->mapItemsForModel($items);

        if (empty($items)) {
            return null; // Return null if no items found
        } else {
            return $items[0]; // Return the first item if found
        }

    }

    public function getItemsByDot(array $usdots, int $paginate = 10000, $groupBy = null)
    {

        $filter = [];
        $filter['dot_number'] = [
            'value' => $usdots,
            'operator' => 'IN'
        ];

        return $this->retrieveItems( $filter, $paginate, $groupBy );

    } 

    public function retrieveItems( array $filter=[], $paginate=10000, $groupBy = '' ) {

        $this->api->setFilter( $filter );

        $items = $this->api->request( $this->apiRoute, $paginate );

        // If mapping to model is enabled, map items
        if ( $this->mapWithModel ) {
            $items = $this->mapItemsForModel($items);
        }

        // Group items if groupBy is specified
        if ( $groupBy ) {
            $items = $this->groupBy($items, $groupBy);
        }

        return $items;

    }

    protected function groupBy(array $items, string $groupBy): array
    {
        $grouped = [];
        foreach ($items as $item) {
            if (isset($item[$groupBy])) {
                $grouped[$item[$groupBy]][] = $item;
            }
        }
        return $grouped;
    }

    protected function parseDate( string $dateString = '' ){

        if (empty($dateString)) {
            return null; // Return null if the date string is empty
        }

        // Extract only the first 8 digits
        if (preg_match('/^(\d{8})/', $dateString, $matches)) {
            $rawDate = $matches[1];
            $year = substr($rawDate, 0, 4);
            $month = substr($rawDate, 4, 2);
            $day = substr($rawDate, 6, 2);

            // Validate as a real date
            if (checkdate((int)$month, (int)$day, (int)$year)) {
                return "$year/$month/$day";
            }
        }

        return null; // Invalid or unparseable format
    }

}