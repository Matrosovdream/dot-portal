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
            'vehicles' => ['repo' => $this->vehicleRepo, 'url' => 'dashboard.vehicles.index'],
            'documents' => ['repo' => $this->fileRepo, 'url' => 'dashboard.documents.index'],
            'drivers' => ['repo' => $this->driverRepo, 'url' => 'dashboard.drivers.index'],
        ];

        foreach ($models as $key => $modelData) {
            $res = $modelData['repo']->modelSearch($request['q'], true, 25) ?? [];
            $count = $res['Model']->count();

            // We don't need model here
            unset($res['Model']);

            $results[$key] = $res;
            $results[$key]['count'] = $count;
            $results[$key]['url'] = route($modelData['url'], ['q' => $request['q']]);
        }

        return $results;

    }

}