<?php

namespace App\View\Components\Dashboard\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Repositories\Notification\NotificationRepo;

class TopbarNotifications extends Component
{

    public $notifications;

    private $notificationsRepo;

    public function __construct()
    {

        $this->notificationsRepo = new NotificationRepo();

        // Get the notifications for the authenticated user
        $this->notifications = $this->getNotifications();

    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.layout.topbar-notifications');
    }

    private function getNotifications()
    {
        // Get notifications by user
        $filter = ['user_id_to' => auth()->user()->id];
        $notifications = $this->notificationsRepo->getAll(
            $filter,
            $paginate = 15
        );

        return $notifications;
    }

}
