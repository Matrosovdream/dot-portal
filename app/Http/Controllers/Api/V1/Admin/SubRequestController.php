<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Api\V1\Admin\SubRequestActions;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionCustomRequest;
use Illuminate\Http\Request;

class SubRequestController extends Controller
{
    public function __construct(protected SubRequestActions $actions) {}

    public function index(Request $request)
    {
        return response()->json($this->actions->index($request));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'              => ['required', 'integer', 'exists:users,id'],
            'subscription_id'      => ['nullable', 'integer', 'exists:subscriptions,id'],
            'user_subscription_id' => ['nullable', 'integer'],
            'request_details'      => ['nullable', 'string'],
            'status_id'            => ['nullable', 'integer'],
        ]);
        return response()->json(['data' => $this->actions->store($data)], 201);
    }

    public function show(SubscriptionCustomRequest $subRequest)
    {
        return response()->json(['data' => $this->actions->show($subRequest)]);
    }

    public function update(Request $request, SubscriptionCustomRequest $subRequest)
    {
        $data = $request->validate([
            'request_details' => ['nullable', 'string'],
            'status_id'       => ['nullable', 'integer'],
        ]);
        return response()->json(['data' => $this->actions->update($subRequest, $data)]);
    }

    public function destroy(SubscriptionCustomRequest $subRequest)
    {
        $this->actions->destroy($subRequest);
        return response()->noContent();
    }

    public function sendEmail(SubscriptionCustomRequest $subRequest)
    {
        $this->actions->sendEmail($subRequest);
        return response()->json(['message' => 'Email queued.']);
    }
}
