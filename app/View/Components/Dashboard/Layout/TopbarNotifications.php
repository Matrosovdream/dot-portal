<?php

namespace App\View\Components\Dashboard\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Repositories\Notification\NotificationRepo;
use Illuminate\Support\Str;

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
            $paginate = 5
        );

        // Loop through items and remove html from the message and short to 150 characters
        foreach ($notifications['items'] as $key=>$item) {

            // Remove html tags and limit the message length to 50 characters
            $item['message'] = strip_tags($item['message']);
            $item['message'] = Str::limit($item['message'], 50, '...');

            // Set detailed link
            //$item['link'] = route('dashboard.notifications.show', ['notification' => $item['id']]);

            $notifications['items'][$key] = $item;
        }

        return $notifications;
    }

}
