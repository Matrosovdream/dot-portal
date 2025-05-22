<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\RequestAdminActions;
use Illuminate\Http\Request;

class RequestAdminController extends Controller
{

    private $requestAdminActions;

    public function __construct()
    {
        $this->requestAdminActions = new RequestAdminActions;
    }

    public function index()
    {
        return view(
            'dashboard.requestmanage.index', 
            $this->requestAdminActions->index()
        );
    }

    public function show($service_id) {

        return view(
            'dashboard.requestmanage.show', 
            $this->requestAdminActions->show($service_id)
        );

    }

    public function updateStatus( Request $request, $service_id)
    {

        $validated = $request->validate([
            'status_id' => 'required|integer',
        ]);

        $data = $this->requestAdminActions->updateStatus($validated, $service_id);
        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function destroy($service)
    {
        $data = $this->requestAdminActions->destroy($service);
        return redirect()->route('dashboard.requestmanage.index');
    }

}
