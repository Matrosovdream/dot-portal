<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Dashboard\RequestUserActions;
use Illuminate\Http\Request;

class RequestUserController extends Controller
{

    private $RequestUserActions;

    public function __construct()
    {
        $this->RequestUserActions = new RequestUserActions;
    }

    public function showGroup( $groupslug )
    {
        return view(
            'dashboard.servicegroups.index', 
            $this->RequestUserActions->showGroup(  $groupslug)
        );
    }

    public function show( $groupslug, $serviceslug )
    {
        return view(
            'dashboard.servicegroups.show', 
            $this->RequestUserActions->show( $groupslug, $serviceslug )
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

        $data = $this->RequestUserActions->store($validated);
        return redirect()->route('dashboard.servicegroups.index');
    }

    public function destroy($service)
    {
        $data = $this->RequestUserActions->destroy($service);
        return redirect()->route('dashboard.servicegroups.index');
    }
}
