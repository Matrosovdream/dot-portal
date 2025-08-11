<?php
namespace App\Actions\Dashboard;

use App\Repositories\Subscription\SubscriptionRequestRepo;


class SubRequestsActions {

    private $reqRepo;

    public function __construct()
    {
        $this->reqRepo = new SubscriptionRequestRepo();

    }

    public function index()
    {
        return [
            'title' => 'Subscription Custom Requests',
            'requests' => $this->reqRepo->getAll(),
            'statuses' => $this->getStatuses(),
        ];
    }

    public function create()
    {
        return [
            'title' => 'Create New Request',
        ];
    }

    public function show( $req_id )
    {
        $req = $this->reqRepo->getByID( $req_id );

        if( request()->has('debug') ) {
            dd($req);
        }

        if( !$req ) {
            abort(404, 'Request not found');
        }

        return [
            'title' => 'Request Details #' . $req['id'],
            'statuses' => $this->getStatuses(),
            'req' => $req,
        ];
    }

    public function update($req_id, $request)
    {
        $req = $this->reqRepo->getByID($req_id);

        if (!$req) {
            abort(404, 'Request not found');
        }

        // Set a custom price for the user subscription 
        $req['userSubscription']['Model']->update( 
            [
                'price_per_driver' => $request->custom_price,
                'price' => $req['userSubscription']['drivers_number'] * $request->custom_price
            ]  
        );

        // Set a status for the request
        $req['Model']->update(
            [
                'status_id' => $request->status_id,
            ]
        );

        return [
            'title' => 'Initial Request Updated #' . $req['id'],
            'req' => $this->reqRepo->getByID($req_id),
        ];
    }

    public function sendEmail($req_id)
    {
        $req = $this->reqRepo->getByID($req_id);

        if (!$req) {
            abort(404, 'Request not found');
        }

        $userService = app('App\Services\User\UserService');

        // Send email to the user
        $user = $req['user']['Model'];
        $userService->sendApprovedRequestEmail( $user );

        // Assuming the email was sent successfully
        return true; // Or return some response indicating success
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