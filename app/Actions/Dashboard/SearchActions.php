<?php
namespace App\Actions\Dashboard;

use App\Repositories\Vehicle\VehicleRepo;
use App\Repositories\File\FileRepo;
use App\Repositories\Driver\DriverRepo;


class SearchActions {

    private $vehicleRepo;
    private $fileRepo;
    private $driverRepo;

    public function __construct()
    {
        $this->vehicleRepo = new VehicleRepo();
        $this->fileRepo = new FileRepo();
        $this->driverRepo = new DriverRepo();

    }

    public function globalSearchAjax( $request )
    {
        $results = [];

        $models = [
            'vehicles' => ['repo' => $this->vehicleRepo, 'url' => 'dashboard.vehicles.index', 'detail_url' => 'dashboard.vehicles.show'],
            'documents' => ['repo' => $this->fileRepo, 'url' => 'dashboard.documents.index', 'detail_url' => 'dashboard.documents.show'],
            'drivers' => ['repo' => $this->driverRepo, 'url' => 'dashboard.drivers.index', 'detail_url' => 'dashboard.drivers.show'],
        ];

        foreach ($models as $key => $modelData) {
            $res = $modelData['repo']->modelSearch($request['q'], true, 25);
            if (empty($res)) {
                continue; // Skip if no results found
            }
            $count = $res['Model']->count();

            // We don't need model here
            unset($res['Model']);

            // Loop through items and add detail links
            foreach ($res['items'] as $item_key=>$item) {
                //$res['items'][$item_key] = route($modelData['detail_url'], $item['id']);
            }

            $results[$key] = $res;
            $results[$key]['count'] = $count;
            $results[$key]['url'] = route($modelData['url'], ['q' => $request['q']]);
        }

        return $results;

    }

}