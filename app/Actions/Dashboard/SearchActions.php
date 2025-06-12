<?php
namespace App\Actions\Dashboard;

use App\Mixins\Integrations\SaferwebAPI;
use App\Models\Service;
use App\Repositories\User\UserTaskRepo;
use App\Helpers\User\CompanyHelper;



class SearchActions {

    private $todoRepo;

    public function __construct()
    {
        $this->todoRepo = new UserTaskRepo();

    }

    public function globalSearchAjax( $request )
    {
        
    }

}