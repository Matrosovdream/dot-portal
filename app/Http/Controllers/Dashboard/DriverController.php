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

    public function show( $driver_id )
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->show($driver_id)
        );
    }

    public function update($driver_id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $data = $this->driverUserActions->update($driver_id, $validated);
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

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $data = $this->driverUserActions->store($validated);
        return redirect()->route('dashboard.drivers.index');
    }

    public function destroy($service)
    {
        $data = $this->driverUserActions->destroy($service);
        
        return redirect()->route('dashboard.drivers.index');
    }

}
