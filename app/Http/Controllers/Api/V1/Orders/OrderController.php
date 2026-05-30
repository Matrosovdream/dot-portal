<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Actions\Api\V1\Orders\OrderActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Orders\OrderListItemResource;
use App\Http\Resources\V1\Orders\OrderPaymentResource;
use App\Http\Resources\V1\Orders\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderActions $actions) {}

    public function index(Request $request)
    {
        return OrderListItemResource::collection($this->actions->index($request, $request->user()));
    }

    public function show(Request $request, Order $order)
    {
        return new OrderResource($this->actions->show($order, $request->user()));
    }

    public function payForm(Request $request, Order $order): JsonResponse
    {
        return response()->json(['data' => $this->actions->payForm($order, $request->user())]);
    }

    public function pay(Request $request, Order $order): JsonResponse
    {
        return $this->actions->pay($order, $request->user());
    }

    public function payments(Request $request, Order $order)
    {
        return OrderPaymentResource::collection($this->actions->payments($order, $request->user()));
    }
}
