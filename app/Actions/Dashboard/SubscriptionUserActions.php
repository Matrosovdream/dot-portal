<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Subscription\SubscriptionRepo;

class SubscriptionUserActions {

    private $subRepo;

    public function __construct()
    {
        $this->subRepo = new SubscriptionRepo();
    }

    public function index()
    {

        // Get subscription by user
        $subscription = $this->subRepo->getByUserID( auth()->user()->id );

        $data = [
            'title' => 'My subscription',
            'subscription' => $subscription,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}