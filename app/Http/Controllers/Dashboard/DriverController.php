<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\DriverUserActions;
use App\Models\Service;

class DriverController extends Controller
{

    private $driverUserActions;

    public function __construct(DriverUserActions $driverUserActions)
    {
        $this->driverUserActions = $driverUserActions;
    }

    public function index()
    {
        return view(
            'dashboard.drivers.index', 
            $this->driverUserActions->index()
        );
    }

    public function show( Service $service )
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->show($service)
        );
    }

    public function update($service, Request $request)
    {
        $data = $this->driverUserActions->update($service, $request);
        
        return redirect()->route('dashboard.drivers.index');
    }

    public function create()
    {
        return view(
            'dashboard.drivers.create', 
            $this->driverUserActions->create()
        );
    }

    public function store(Request $request)
    {
        $data = $this->driverUserActions->store($request);

        return redirect()->route('dashboard.drivers.index');
    }

    public function destroy($service)
    {
        $data = $this->driverUserActions->destroy($service);
        
        return redirect()->route('dashboard.drivers.index');
    }

}
