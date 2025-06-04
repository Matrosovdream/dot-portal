<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\ServiceGroupActions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\CanDeleteServiceGroup;

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

    public function update($group_id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'slug' => [
                'required',
                Rule::unique('ref_service_groups', 'slug')->ignore($group_id),
            ],
            'description' => 'nullable',
            'is_active' => 'nullable'
        ]);

        $data = $this->serviceGroupActions->update($group_id, $validated);

        return redirect()->back();

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
            'slug' => [
                'required',
                Rule::unique('ref_service_groups', 'slug'),
            ],
            'description' => 'nullable',
            'is_active' => 'nullable'
        ]);

        $data = $this->serviceGroupActions->store($validated);

        return redirect()->route('dashboard.servicegroups.index');
    }


    public function destroy(Request $request, $serviceGroupId)
    {
        // Inject the ID manually for validation
        $request->merge(['service_group_id' => $serviceGroupId]);

        $request->validate([
            //'service_group_id' => [new CanDeleteServiceGroup]
        ]);

        $this->serviceGroupActions->destroy($serviceGroupId);

        return redirect()->route('dashboard.servicegroups.index');
    }

}
