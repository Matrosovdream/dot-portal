<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\ServiceFieldActions;
use Illuminate\Http\Request;

class ServiceFieldsController extends Controller
{

    private $serviceFieldActions;

    public function __construct()
    {
        $this->serviceFieldActions = new ServiceFieldActions;
    }


    public function index()
    {
        //dd($this->serviceFieldActions->index());
        return view(
            'dashboard.servicefields.index', 
            $this->serviceFieldActions->index()
        );
    }

    public function show( $driver_id )
    {
        return view(
            'dashboard.servicefields.show', 
            $this->serviceFieldActions->show($driver_id)
        );
    }

    public function update($driver_id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $data = $this->serviceFieldActions->update($driver_id, $validated);
        return redirect()->route('dashboard.servicefields.index');
    }

    public function create()
    {
        return view(
            'dashboard.servicefields.create', 
            $this->serviceFieldActions->create()
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

        $data = $this->serviceFieldActions->store($validated);
        return redirect()->route('dashboard.servicefields.index');
    }

    public function destroy($service)
    {
        $data = $this->serviceFieldActions->destroy($service);
        
        return redirect()->route('dashboard.servicefields.index');
    }
}
