<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Dashboard\ProfileUserActions;


class ProfileCompanyController {

    private $userProfileActions;

    public function __construct()
    {
        $this->userProfileActions = new ProfileUserActions();
    }

    public function profilePreview()
    {
        return view(
            'dashboard.profile.show', 
            $this->userProfileActions->profilePreview()
            );
    }

    public function profileEdit()
    {
        return view(
            'dashboard.profile.show', 
            $this->userProfileActions->profileEdit()
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

        $data = $this->userProfileActions->profileUpdate($validated);
        
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

        $data = $this->userProfileActions->profileAddressUpdate($validated);
        
        return redirect()->route('dashboard.profile.edit');
    }

    public function companyEdit()
    {
        return view(
            'dashboard.profile.show', 
            $this->userProfileActions->companyEdit()
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

        $data = $this->userProfileActions->companyUpdate($validated);
        
        return redirect()->route('dashboard.profile.company.edit');
    }

}