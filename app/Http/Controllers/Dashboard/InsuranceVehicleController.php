<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\InsuranceVehicleActions;


class InsuranceVehicleController extends Controller
{

    private $insuranceActions;

    public function __construct()
    {
        $this->insuranceActions = new InsuranceVehicleActions();
    }

    public function index()
    {
        $insurances = $this->insuranceActions->index();
        return view('dashboard.insurance.index', $insurances);
    }

    public function create()
    {
        $data = $this->insuranceActions->create();
        return view('dashboard.insurance.create', $data);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            //'vehicle_ids' => 'nullable|array',
        ]);

        $this->insuranceActions->store($validated);
        return redirect()->route('dashboard.insurance-vehicles.index')->with('success', 'Insurance created successfully.');
    }

    public function show($id)
    {
        $data = $this->insuranceActions->show($id);
        return view('dashboard.insurance.show', $data);
    }

    public function profile($id)
    {
        $data = $this->insuranceActions->show($id);
        return view('dashboard.insurance.show', $data);
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            '*_remove' => 'nullable',
        ]);

        $this->insuranceActions->update($validated, $id);
        return redirect()->back()->with('success', 'Insurance updated successfully.');
    }

    public function destroy($id)
    {
        $this->insuranceActions->destroy($id);
        return redirect()->route('dashboard.insurance.index')->with('success', 'Insurance deleted successfully.');
    }

}
