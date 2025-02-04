<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\NotificationUserActions;

class NotificationsController extends Controller
{

    private $notificationUserActions;

    public function __construct()
    {
        $this->notificationUserActions = new NotificationUserActions();
    }
    
    public function index()
    {
        return view('dashboard.notifications.user', $this->notificationUserActions->index());
    }

}
