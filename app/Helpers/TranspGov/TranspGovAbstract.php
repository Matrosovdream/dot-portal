<?php

namespace App\Helpers\TranspGov;

class TranspGovAbstract {

    protected $api;
    protected $apiRoute = '';

    public function retrieveItems( array $filter=[], $paginate=10000, $groupBy = '' ) {

        $this->api->setFilter( $filter );

        $items = $this->api->request( $this->apiRoute );

        // Group items if groupBy is specified
        if ($groupBy) {
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