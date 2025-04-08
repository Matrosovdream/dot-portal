<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
        return view('dashboard.insurance.index', compact('insurances'));
    }

    public function create()
    {
        return view('dashboard.insurance.create');
    }

    public function store(Request $request)
    {
        $this->insuranceActions->store($request);
        return redirect()->route('dashboard.insurance.index')->with('success', 'Insurance created successfully.');
    }

    public function show($id)
    {
        $insurance = $this->insuranceActions->edit($id);
        return view('dashboard.insurance.edit', compact('insurance'));
    }

    public function update(Request $request, $id)
    {
        $this->insuranceActions->update($request, $id);
        return redirect()->route('dashboard.insurance.index')->with('success', 'Insurance updated successfully.');
    }

    public function destroy($id)
    {
        $this->insuranceActions->destroy($id);
        return redirect()->route('dashboard.insurance.index')->with('success', 'Insurance deleted successfully.');
    }

}
