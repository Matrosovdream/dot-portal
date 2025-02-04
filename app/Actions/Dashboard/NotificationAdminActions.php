<?php
namespace App\Actions\Dashboard;

use App\Helpers\adminSettingsHelper;
use App\Repositories\Notification\NotificationRepo;

class NotificationAdminActions {

    private $notificationsRepo;

    public function __construct()
    {
        $this->notificationsRepo = new NotificationRepo();
    }

    public function index()
    {

        // Get notifications by user
        $filter = [
            'type' => 'info',
        ];
        $notifications = $this->notificationsRepo->getAll( 
            $filter, 
            $paginate = 20 
        );

        //dd($notifications);

        $data = [
            'title' => 'Notification Manager',
            'notifications' => $notifications,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create Notification',
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function store($request)
    {
        $this->notificationsRepo->create( $request );

        return redirect()->route('dashboard.notifications-manage.index');
    }

    public function show($id)
    {
        $notification = $this->notificationsRepo->getByID( $id );

        $data = [
            'title' => 'Notification Details',
            'notification' => $notification,
            'sidebarMenu' => adminSettingsHelper::getSidebarMenu(),
        ];

        return $data;
    }

    public function update($request, $id)
    {
        $this->notificationsRepo->update( $id, $request );

        return redirect()->route('dashboard.notifications-manage.index');
    }

    public function destroy($id)
    {
        $this->notificationsRepo->delete( $id );

        return redirect()->route('dashboard.notifications-manage.index');
    }

}