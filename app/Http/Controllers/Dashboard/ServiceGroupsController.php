<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\ServiceGroupActions;
use Illuminate\Http\Request;

class ServiceGroupsController extends Controller
{

    private $serviceGroupActions;

    public function __construct()
    {
        $this->serviceGroupActions = new ServiceGroupActions;
    }


    public function index()
    {
        return view(
            'dashboard.servicegroups.index', 
            $this->serviceGroupActions->index()
        );
    }

    public function show( $driver_id )
    {
        return view(
            'dashboard.servicegroups.show', 
            $this->serviceGroupActions->show($driver_id)
        );
    }

    public function update($driver_id, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'nullable',
            'is_active' => 'nullable'
        ]);

        $data = $this->serviceGroupActions->update($driver_id, $validated);

        if (isset($data['error'])) {
            return redirect()->back()->withErrors($data['message']);
        } else {
            return redirect()->back();
        }
    }

    public function create()
    {
        return view(
            'dashboard.servicegroups.create', 
            $this->serviceGroupActions->create()
        );
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'nullable',
            'is_active' => 'nullable'
        ]);

        $data = $this->serviceGroupActions->store($validated);

        if (isset($data['error'])) {
            return redirect()->back()->withErrors($data['message']);
        } else {
            return redirect()->route('dashboard.servicegroups.index');
        }
        
    }

    public function destroy($service)
    {
        $data = $this->serviceGroupActions->destroy($service);
        return redirect()->route('dashboard.servicegroups.index');
    }
}
