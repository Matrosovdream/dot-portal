<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;

class SubscriptionUserActions {

    private $userRepo;
    private $userSubRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->userSubRepo = new UserSubscriptionRepo();
    }

    public function index()
    {

        $data = [
            'title' => 'My subscription',
            'user' => $this->userRepo->getByID( auth()->user()->id ),
            'subscription' => $this->userSubRepo->getByUserID( auth()->user()->id ),
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}