<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ServiceAdminActions;
use App\Models\Service;

class ServiceController extends Controller
{

    private $serviceAdminActions;

    public function __construct(ServiceAdminActions $serviceAdminActions)
    {
        $this->serviceAdminActions = $serviceAdminActions;
    }

    public function index()
    {
        return view(
            'dashboard.services.index', 
            $this->serviceAdminActions->index()
        );
    }

    public function show( Service $service )
    {
        return view(
            'dashboard.services.show', 
            $this->serviceAdminActions->show($service)
        );
    }

    public function update($service, Request $request)
    {
        $data = $this->serviceAdminActions->update($service, $request);
        
        return redirect()->route('dashboard.services.index');
    }

    public function create()
    {
        return view(
            'dashboard.services.create', 
            $this->serviceAdminActions->create()
        );
    }

    public function store(Request $request)
    {
        $data = $this->serviceAdminActions->store($request);

        return redirect()->route('dashboard.services.index');
    }

    public function destroy($service)
    {
        $data = $this->serviceAdminActions->destroy($service);
        
        return redirect()->route('dashboard.services.index');
    }

}
