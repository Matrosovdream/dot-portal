<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Notification\NotificationRepo;

class NotificationUserActions {

    private $notificationsRepo;

    public function __construct()
    {
        $this->notificationsRepo = new NotificationRepo();
    }

    public function index()
    {

        // Get notifications by user
        $filter = ['user_id_to' => auth()->user()->id];
        $notifications = $this->notificationsRepo->getAll( 
            $filter, 
            $paginate = 10 
        );

        //dd($notifications);

        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

}