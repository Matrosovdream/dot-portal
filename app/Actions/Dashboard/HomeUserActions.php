<?php
namespace App\Actions\Dashboard;

use App\Models\Service;
use App\Repositories\User\UserRepo;
use App\Repositories\Driver\DriverRepo;
use App\Repositories\Vehicle\VehicleRepo;
use App\Repositories\Insurance\InsuranceVehicleRepo;
use App\Repositories\Vehicle\VehicleInspectionsSaferwebRepo;
use App\Repositories\Vehicle\VehicleCrashesSaferwebRepo;

use App\Mail\Company\CompanyWelcomeMail;
use Illuminate\Support\Facades\Mail;

use App\Services\MailgunService;

class HomeUserActions {

    private $userRepo;
    private $driverRepo;
    private $vehicleRepo;
    private $insuranceRepo;
    private $inspectionsRepo;
    private $crashesRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo;
        $this->driverRepo = new DriverRepo;
        $this->vehicleRepo = new VehicleRepo;
        $this->insuranceRepo = new InsuranceVehicleRepo;
        $this->inspectionsRepo = new VehicleInspectionsSaferwebRepo;
        $this->crashesRepo = new VehicleCrashesSaferwebRepo;
    }

    public function index()
    {

        $user = auth()->user();

        $data = [
            'title' => 'Services list',
            'user' => $this->userRepo->getById( auth()->user()->id ),
            'stats' => $this->getStats(),
            'saferwebLatest' => $this->getLatestSaferweb( $user->company->id ?? $user->id, 5 ),
            'services' => Service::paginate(10),
        ];

        if( $user->isCompany() ) {
            $data['company']['saferweb'] = $this->userRepo->getCompanySaferweb( $user->id );

            if(
                $data['stats']['drivers']['total'] == 0 ||
                $data['stats']['vehicles']['total'] == 0
            ) {
                $data['company']['banner_new_company'] = true;
            }

        }

        /*
        $service = new MailgunService();
        $mailRes = $service->sendTemplate(
            'matrosovdream@gmail.com',
            'welcome-company',
            ['firstname' => 'Stan']
        );

        dd($mailRes);
        */


        return $data;
    }

    public function getStats() {

        $user = auth()->user();

        $stats = [
            'drivers' => $this->driverRepo->getCompanyStats( $user->id ),
            'vehicles' => $this->vehicleRepo->getCompanyStats( $user->company->id ?? $user->id ),
            'insurances' => $this->insuranceRepo->getCompanyStats( $user->id ),
        ];

        if( $user->isCompany() ) {

            $stats['inspections']['last_update'] = dateFormat( date('Y-m-d') );
            
            // Inspections today
            $stats['inspections']['day'] = $this->getSaferwebCount(
                $this->inspectionsRepo,
                ['report_date' => date('Y-m-d')]
            );

            // Inspections this month
            $stats['inspections']['month'] = $this->getSaferwebCount(
                $this->inspectionsRepo,
                ['report_date' => date('Y-m-01')]
            );

            $stats['crashes']['last_update'] = dateFormat(date('Y-m-d'));

            // Crashes today
            $stats['crashes']['day'] = $this->getSaferwebCount(
                $this->crashesRepo,
                ['report_date' => date('Y-m-d')]
            );

            // Crashes this month
            $stats['crashes']['month'] = $this->getSaferwebCount(
                $this->crashesRepo,
                ['report_date' => date('Y-m-01')]
            );
           
        }

        return $stats;

    }

    public function getLatestSaferweb( $company_id, $limit = 2 ) {

        // Get latest inspections
        $inspections = $this->inspectionsRepo->getAll(
            ['company_id' => $company_id],
            $paginate = $limit,
            ['report_date' => 'desc']
        );

        // Get latest crashes
        $crashes = $this->crashesRepo->getAll(
            ['company_id' => $company_id],
            $paginate = $limit,
            ['report_date' => 'desc']
        );

        return [
            'inspections' => $inspections,
            'crashes' => $crashes
        ];

    } 

    public function getSaferwebCount( $repo, $filterSet = [] ) {

        if( 
            !isset( auth()->user()->company->id )
            ) {
            return null; 
        }

        $filter = ['company_id' => auth()->user()->company->id];

        $filter = array_merge($filter, $filterSet);

        return $repo->getAll(
            $filter,
            $paginate = 1
        )['Model']->count();

    }

}