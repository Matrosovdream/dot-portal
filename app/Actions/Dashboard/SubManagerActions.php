<?php
namespace App\Actions\Dashboard;

use App\Repositories\User\UserRepo;
use App\Repositories\User\UserSubscriptionRepo;
use App\Repositories\Subscription\SubscriptionRepo;
use App\Repositories\User\UserCompanyRepo;
use App\Services\User\UserService;


class SubManagerActions {

    public function __construct(
        protected UserService $userService,
        protected UserRepo $userRepo,
        protected UserSubscriptionRepo $userSubRepo,
        protected UserCompanyRepo $userCompanyRepo,
        protected SubscriptionRepo $subListRepo,
    )
    {}

    public function index()
    {

        $filter = [];

        // Filter by search form
        if( request()->has('q') && !empty(request()->input('q')) ) {

            $subsFound = $this->userSubRepo->modelSearch( request()->input('q'), false);
            $sub_ids = $subsFound->pluck('id')->toArray();

            if( empty($sub_ids) ) {
                $filter['id'] = [0];
            } else {
                $filter['id'] = $sub_ids; 
            }
            
        }

        $this->userSubRepo->setRelations(['user', 'subscription']);
        $subs = $this->userSubRepo->getAll( $filter );

        return [
            'title' => 'User Subscriptions',
            'subs' => $subs,
            'statuses' => $this->getStatuses(),
        ];
    }

    public function create()
    {
        //dd($this->subListRepo->getAll());
        return [
            'title' => 'Create New Subscription',
            'subList' => $this->subListRepo->getAll()
        ];
    }

    public function store($request)
    {

        // Create a new user and assign role
        $user = $this->userRepo->create($request->user);
        $user['Model']->setRole('company');

        

        if( $user ) {

            // Sync user company
            $userCompany = $this->userCompanyRepo->syncItem($user['id'], $request->company);

            // Sync subscription details
            $userSub = $this->userSubRepo->syncItem(
                $user['id'],
                $request->sub + [
                    'user_id' => $user['id'],
                    'status' => 'disabled', // Default status
                ]
            );

            // If payment link is requested, send it
            if( $request->has('send_payment_link') ) {
                $this->userService->sendPaymentLink( $user['Model'] );
            }

            if( $userCompany && $userSub ) {
                return [
                    'error' => false,
                    'user' => $user,
                    'sub' => $userSub,
                ];
            } 

        } else {
            return [
                'error' => true,
                'message' => 'User creation failed, please try again.',
            ];
        }

        return true;
    }

    public function show( $sub_id )
    {

        $sub = $this->getSub( $sub_id );

        if( !$sub ) {
            abort(404, 'Subscription not found');
        }
        $data =  [
            'title' => 'Subscription Details #' . $sub['id'],
            'statuses' => $this->getStatuses(),
            'sub' => $sub,
            'user' => $sub['user'],
            'company' => $sub['user']['company'],
            'subList' => $this->subListRepo->getAll()
        ];

        if( request()->has('debug') ) {
            dd($data);
        }

        return $data;
    }

    public function update($sub_id, $request)
    {

        $sub = $this->getSub( $sub_id );

        $this->userSubRepo->update(
            $sub_id,
            $request
        );

        return true;
    }

    public function userStore($sub_id, $request)
    {
        $sub = $this->getSub( $sub_id );
        $sub['user']['Model']->update( $request );
        return true;
    }

    public function companyStore($sub_id, $request)
    {
        $sub = $this->getSub( $sub_id );
        $sub['user']['company']['Model']->update( $request );
        return true;
    }

    public function sendOnceLogin($sub_id)
    {
        $sub = $this->getSub( $sub_id );

        // Send one-time login link to the user
        $this->userService->sendOnceLoginLink($sub['user']['Model']);

        // Assuming the email was sent successfully
        return true; 
    }

    public function sendPaymentLink($sub_id)
    {
        $sub = $this->getSub( $sub_id );

        // Send one-time login link to the user
        $this->userService->sendPaymentLink($sub['user']['Model'], 100);

        return true; 
    }

    private function getSub($sub_id)
    {
        $this->userSubRepo->setRelations(['user', 'subscription']);
        $sub = $this->userSubRepo->getByID($sub_id);

        if (!$sub) {
            abort(404, 'Subscription not found');
        }

        return $sub;
    }

    private function getStatuses() {

        return [
            '1' => 'Pending',
            '2' => 'Approved',
            '3' => 'Rejected',
            '4' => 'Completed',
        ];

    }

}