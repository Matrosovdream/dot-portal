<?php
namespace App\Actions\Dashboard;

use App\Helpers\TranspGov\TranspGovSnapshot;
use App\Helpers\TranspGov\TranspGovInspection;
use App\Helpers\TranspGov\TranspGovCrash;
use App\Mixins\Integrations\SaferwebAPI;
use App\Models\Service;
use App\Repositories\User\UserTaskRepo;
use App\Helpers\User\CompanyHelper;
use App\Helpers\User\UserTaskHelper;



class ClearingHouseActions {

    private $todoRepo;

    public function __construct()
    {
        $this->todoRepo = new UserTaskRepo();

    }


    public function index()
    {
        $data = [
            'title' => 'Company To-Do List',
        ];

        return $data;
    }

}