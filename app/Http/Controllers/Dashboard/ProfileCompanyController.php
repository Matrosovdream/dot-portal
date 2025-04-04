<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ProfileCompanyActions;


class ProfileCompanyController {

    private $profileCompanyActions;

    public function __construct()
    {
        $this->profileCompanyActions = new ProfileCompanyActions();
    }

    public function profilePreview()
    {
        return view(
            'dashboard.profile.show', 
            $this->profileCompanyActions->profilePreview()
            );
    }

    public function profileEdit()
    {
        return view(
            'dashboard.profile.show', 
            $this->profileCompanyActions->profileEdit()
            );
    }

    public function profileUpdate(Request $request)
    {

        $validated = $request->validate([
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'phone' => 'nullable',
            'birthday' => 'nullable'
        ]);

        $data = $this->profileCompanyActions->profileUpdate($validated);
        
        return redirect()->route('dashboard.profile.edit');
    }

    public function profileAddressUpdate(Request $request)
    {

        $validated = $request->validate([
            'address1' => 'nullable',
            'address2' => 'nullable',
            'city' => 'nullable',
            'state_id' => 'nullable',
            'zip' => 'nullable',
        ]);

        $data = $this->profileCompanyActions->profileAddressUpdate($validated);
        
        return redirect()->route('dashboard.profile.edit');
    }

    public function companyEdit()
    {
        return view(
            'dashboard.profile.show', 
            $this->profileCompanyActions->companyEdit()
            );
    }

    public function companyUpdate(Request $request)
    {
  
        $validated = $request->validate([
            'name' => 'nullable',
            'phone' => 'nullable',
            'dot_number' => 'nullable',
            'mc_number' => 'nullable',
            'business.address1' => 'nullable',
            'business.address2' => 'nullable',
            'business.city' => 'nullable',
            'business.state_id' => 'nullable',
            'business.zip' => 'nullable',
            'mailing.address1' => 'nullable',
            'mailing.address2' => 'nullable',
            'mailing.city' => 'nullable',
            'mailing.state_id' => 'nullable',
            'mailing.zip' => 'nullable',
        ]);

        $data = $this->profileCompanyActions->companyUpdate($validated);
        
        return redirect()->route('dashboard.profile.company.edit');
    }

    // Driver license
    public function driverLicense()
    {
        return view(
            'dashboard.profile.show', 
            $this->profileCompanyActions->driverLicense()
            );
    }

    public function updateDriverLicense(Request $request)
    {
        $validated = $request->validate([
            'license_number' => 'nullable',
            'state_id' => 'nullable',
            'expiration_date' => 'nullable',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $data = $this->profileCompanyActions->updateDriverLicense($validated);
        
        return redirect()->route('dashboard.profile.driver-license.edit');
    }

    public function medicalCard()
    {
        return view(
            'dashboard.profile.show', 
            $this->profileCompanyActions->medicalCard()
            );
    }

    public function updateMedicalCard(Request $request)
    {
        $validated = $request->validate([
            'expiration_date' => 'nullable',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $data = $this->profileCompanyActions->updateMedicalCard($validated);
        
        return redirect()->route('dashboard.profile.medical-card.edit');
    }

}