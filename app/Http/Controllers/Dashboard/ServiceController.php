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
            'description' => 'required',
            'price' => 'required',
        ]);

        $data = $this->serviceAdminActions->update($service_id, $request);
        
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

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        $data = $this->serviceAdminActions->store($request);

        return redirect()->route('dashboard.services.index');
    }

    public function destroy($service_id)
    {
        $data = $this->serviceAdminActions->delete($service_id);
        
        return redirect()->route('dashboard.services.index');
    }

}
