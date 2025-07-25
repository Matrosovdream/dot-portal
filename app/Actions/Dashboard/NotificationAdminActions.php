<?php
namespace App\Actions\Dashboard;

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

        // Filter by search form
        if( request()->has('q') ) {
            $filter['title'] = '%' . request()->input('q') . '%';
        }

        $notifications = $this->notificationsRepo->getAll( 
            $filter, 
            $paginate = 20 
        );

        //dd($notifications);

        $data = [
            'title' => 'Notification Manager',
            'notifications' => $notifications
        ];

        return $data;
    }

    public function create()
    {
        $data = [
            'title' => 'Create Notification'
        ];

        return $data;
    }

    public function store($request)
    {

        // Set user ID
        $request['user_id'] = auth()->user()->id;

        $this->notificationsRepo->create( $request );

        return redirect()->route('dashboard.notifications-manage.index');
    }

    public function show($id)
    {
        $notification = $this->notificationsRepo->getByID( $id );

        $data = [
            'title' => 'Notification Details',
            'notification' => $notification
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