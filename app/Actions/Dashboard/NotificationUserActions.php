<?php
namespace App\Actions\Dashboard;

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
        
        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications
        ];

        return $data;
    }

}