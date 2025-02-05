<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\NotificationAdminActions;

class NotificationsAdminController extends Controller
{

    private $NotificationAdminActions;

    public function __construct()
    {
        $this->NotificationAdminActions = new NotificationAdminActions();
    }
    
    public function index()
    {
        return view('dashboard.notifications-manage.index', $this->NotificationAdminActions->index());
    }

    public function create()
    {
        return view('dashboard.notifications-manage.create', $this->NotificationAdminActions->create());
    }

    public function store( Request $request )
    {

        // Validate
        $validated = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'type' => 'required',
        ]);

        $this->NotificationAdminActions->store( $validated );
        return redirect()->route('dashboard.notifications-manage.index');
    }

    public function show($id)
    {
        return view('dashboard.notifications-manage.show', $this->NotificationAdminActions->show($id));
    }

    public function edit($id)
    {
        return view('dashboard.notifications-manage.edit', $this->NotificationAdminActions->edit($id));
    }

    public function update($id, Request $request)
    {

        // Validate
        $validated = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'type' => 'required',
        ]);

        $this->NotificationAdminActions->update($validated, $id);
        return redirect()->route('dashboard.notifications-manage.index');
    }

    public function destroy($id)
    {
        $this->NotificationAdminActions->destroy($id);
        return redirect()->route('dashboard.notifications-manage.index');
    }

}
