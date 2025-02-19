<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\DriverUserActions;

class DriverController extends Controller
{

    private $driverUserActions;

    public function __construct(DriverUserActions $driverUserActions)
    {
        $this->driverUserActions = $driverUserActions;
    }

    public function index()
    {
        //dd($this->driverUserActions->index());
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
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'dob' => 'nullable|date',
            'ssn' => 'nullable',
            'hire_date' => 'nullable|date',
            'driver_type_id' => 'required',
        ]);

        $data = $this->driverUserActions->store($validated);
        return redirect()->route('dashboard.drivers.index');
    }

    public function destroy($service)
    {
        $data = $this->driverUserActions->destroy($service);
        
        return redirect()->route('dashboard.drivers.index');
    }

    public function profile($driver_id)
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->profile($driver_id)
        );
    }

    public function updateProfile($driver_id, Request $request)
    {

        $validated = $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'dob' => 'nullable|date',
            'ssn' => 'nullable',
            'hire_date' => 'nullable|date',
            'driver_type_id' => 'required',
        ]);

        $data = $this->driverUserActions->updateProfile($driver_id, $validated);
        return redirect()->route('dashboard.drivers.show.profile', $driver_id);
    }

    public function license($driver_id)
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->license($driver_id)
        );
    }

    public function updateLicense($driver_id, Request $request)
    {

        $validated = $request->validate([
            'endorsement_id' => 'nullable',
            'license_number' => 'nullable',
            'expiration_date' => 'nullable',
            'state_id' => 'nullable',
        ]);

        $data = $this->driverUserActions->updateLicense($driver_id, $validated);
        return redirect()->route('dashboard.drivers.show.license', $driver_id);
    }

    public function medicalCard($driver_id)
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->medicalCard($driver_id)
        );
    }

    public function updateMedicalCard($driver_id, Request $request)
    {

        $validated = $request->validate([
            'examiner_name' => 'nullable',
            'national_registry' => 'nullable',
            'issue_date' => 'nullable',
            'expiration_date' => 'nullable',
        ]);

        $data = $this->driverUserActions->updateMedicalCard($driver_id, $validated);
        return redirect()->route('dashboard.drivers.show.medicalcard', $driver_id);
    }

    public function address($driver_id)
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->address($driver_id)
        );
    }

    public function updateAddress($driver_id, Request $request)
    {

        $validated = $request->validate([
            'address1' => 'nullable',
            'address2' => 'nullable',
            'city' => 'nullable',
            'state_id' => 'nullable',
            'zip' => 'required',
        ]);

        $data = $this->driverUserActions->updateAddress($driver_id, $validated);
        return redirect()->route('dashboard.drivers.show.address', $driver_id);
    }

    public function logs($driver_id)
    {
        return view(
            'dashboard.drivers.show', 
            $this->driverUserActions->logs($driver_id)
        );
    }

}
