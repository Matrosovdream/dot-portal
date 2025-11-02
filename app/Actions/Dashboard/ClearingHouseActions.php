<?php
namespace App\Actions\Dashboard;

use App\Repositories\Driver\DriverRepo;
use App\Repositories\User\UserRepo;


class ClearingHouseActions {

    public function __construct(
        private DriverRepo $driverRepo,
        private UserRepo $userRepo
    )
    {

    }

    public function index( $request )
    {

        $user = $this->userRepo->getById( auth()->user()->id );

        $data = [
            'title' => 'Clearing House Management',
            'user' => $user,
            'company' => $user['company'] ?? null,
            'drivers' => $this->driverRepo->getUserDrivers( auth()->user()->id ),
        ];

        return $data;
    }

    public function buyQueriesForm( $request )
    {
        $data = [
            'title' => 'Buy Queries',
        ];

        return $data;

    }

}