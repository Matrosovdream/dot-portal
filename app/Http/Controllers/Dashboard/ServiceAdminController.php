<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ServiceAdminActions;


class ServiceAdminController extends Controller
{

    private $serviceAdminActions;

    public function __construct()
    {
        $this->serviceAdminActions = new ServiceAdminActions();
    }

    public function index()
    {
        return view(
            'dashboard.services.index', 
            $this->serviceAdminActions->index()
        );
    }

    public function show( $service_id )
    {
        return view(
            'dashboard.services.show', 
            $this->serviceAdminActions->show($service_id)
        );
    }

    public function update($service_id, Request $request)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'nullable',
        ]);

        $data = $this->serviceAdminActions->update($service_id, $request);
        
        return redirect()->route('dashboard.services.index');
    }

    public function updateServiceStatus($service_id, Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $data = $this->serviceAdminActions->updateServiceStatus($service_id, $request);
        return redirect()->back()->with('success','Service status updated successfully');
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

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'nullable',
            'price' => 'nullable',
        ]);

        $data = $this->serviceAdminActions->store($validated);

        return redirect()->route('dashboard.services.index');
    }

    public function destroy($service_id)
    {
        $data = $this->serviceAdminActions->delete($service_id);
        
        return redirect()->route('dashboard.services.index');
    }

    public function storeField( $service_id, Request $request ) {

        $validated = $request->validate([
            'field_id' => 'required',
            'entity' => 'nullable',
            'required' => 'nullable',
            'default_value' => 'nullable',
            'placeholder' => 'nullable',
            'classes' => 'nullable',
            'order' => 'nullable',
        ]);

        $data = $this->serviceAdminActions->storeField($service_id, $validated);

        return redirect()->route('dashboard.services.show', $service_id);

    }

    public function updateField( $service_id, $field_id, Request $request ) {

        $validated = $request->validate([
            'field_id' => 'required',
            'entity' => 'nullable',
            'required' => 'nullable',
            'default_value' => 'nullable',
            'placeholder' => 'nullable',
            'classes' => 'nullable',
            'order' => 'nullable',
        ]);
        $data = $this->serviceAdminActions->storeField($service_id, $validated, $field_id);

        return redirect()->route('dashboard.services.show', $service_id);

    }

    public function destroyField( $service_id, $field_id ) {

        $data = $this->serviceAdminActions->deleteField($service_id, $field_id);

        return redirect()->route('dashboard.services.show', $service_id);

    }

}
