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

        $items = $this->api->request( $this->apiRoute );

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

}