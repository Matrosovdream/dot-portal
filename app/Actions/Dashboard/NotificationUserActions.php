<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
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
        $notifications = $this->notificationsRepo->getUserNotifications( 
            auth()->user()->id, 
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