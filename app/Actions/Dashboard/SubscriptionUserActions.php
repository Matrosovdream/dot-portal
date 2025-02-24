<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\User\UserSubscriptionRepo;

class SubscriptionUserActions {

    private $userSubRepo;

    public function __construct()
    {
        $this->userSubRepo = new UserSubscriptionRepo();
    }

    public function index()
    {

        // Get subscription by user
        $subscription = $this->userSubRepo->getByUserID( auth()->user()->id );

        //dd($subscription);

        $data = [
            'title' => 'My subscription',
            'subscription' => $subscription,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}