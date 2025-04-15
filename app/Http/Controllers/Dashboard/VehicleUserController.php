<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\VehicleUserActions;

class VehicleUserController extends Controller
{

    private $vehicleUserActions;

    public function __construct(VehicleUserActions $vehicleUserActions)
    {
        $this->vehicleUserActions = $vehicleUserActions;
    }

    public function index()
    {
        return view(
            'dashboard.vehicles.index', 
            $this->vehicleUserActions->index()
        );
    }

    public function show( $driver_id )
    {
        return view(
            'dashboard.vehicles.show', 
            $this->vehicleUserActions->show($driver_id)
        );
    }

    public function profile($driver_id)
    {
        return view(
            'dashboard.vehicles.show', 
            $this->vehicleUserActions->profile($driver_id)
        );
    }

    public function updateProfile($driver_id, Request $request)
    {

        $validated = $request->validate([
            'number' => 'required',
            'vin' => 'required',
            'unit_type_id' => 'required',
            'ownership_type_id' => 'required',
            'driver_id' => 'nullable',
            'reg_expire_date' => 'nullable',
            'inspection_expire_date' => 'nullable',
        ]);

        $data = $this->vehicleUserActions->update($driver_id, $validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function update($driver_id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $data = $this->vehicleUserActions->update($driver_id, $validated);
        return redirect()->route('dashboard.vehicles.index');
    }

    public function mvr($driver_id)
    {
        return view(
            'dashboard.vehicles.show', 
            $this->vehicleUserActions->mvr($driver_id)
        );
    }

    public function updateMvr($driver_id, Request $request)
    {
        $validated = $request->validate([
            'mvr_number' => 'required',
            'mvr_date' => 'required|date',
        ]);

        $data = $this->vehicleUserActions->updateMvr($driver_id, $validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function insurance($driver_id)
    {
        return view(
            'dashboard.vehicles.show', 
            $this->vehicleUserActions->insurance($driver_id)
        );
    }

    public function inspections($driver_id)
    {
        return view(
            'dashboard.vehicles.show', 
            $this->vehicleUserActions->inspections($driver_id)
        );
    }

    public function storeInspection($driver_id, Request $request)
    {
        $validated = $request->validate([
            'inspection_number' => 'required',
            'inspection_date' => 'required|date',
        ]);

        $data = $this->vehicleUserActions->storeInspection($driver_id, $validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function updateInspection($driver_id, Request $request)
    {
        $validated = $request->validate([
            'inspection_id' => 'required',
        ]);

        $data = $this->vehicleUserActions->updateInspection($driver_id, $validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function updateInsurance($driver_id, Request $request)
    {
        $validated = $request->validate([
            'insurance_id' => 'required',
        ]);

        $data = $this->vehicleUserActions->updateInsurance($driver_id, $validated);
        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function create()
    {
        return view(
            'dashboard.vehicles.create', 
            $this->vehicleUserActions->create()
        );
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'number' => 'required',
            'vin' => 'required',
            'unit_type_id' => 'required',
            'ownership_type_id' => 'required',
            'driver_id' => 'nullable',
            'reg_expire_date' => 'nullable',
            'inspection_expire_date' => 'nullable',
        ]);

        $data = $this->vehicleUserActions->store($validated);
        return redirect()->route('dashboard.vehicles.index');
    }

    public function destroy($service)
    {
        $data = $this->vehicleUserActions->destroy($service);
        
        return redirect()->route('dashboard.vehicles.index');
    }

}
