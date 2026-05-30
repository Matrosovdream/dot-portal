<?php

namespace App\Actions\Api\V1\Orders;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\RefPaymentMethod;
use App\Models\User;
use App\Models\UserPaymentCard;
use App\Repositories\Order\OrderRepo;
use App\Repositories\Order\OrderPaymentRepo;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderActions
{
    public function __construct(
        protected OrderRepo        $orderRepo,
        protected OrderPaymentRepo $orderPaymentRepo,
        protected PaymentService   $paymentService,
    ) {}

    public function index(Request $request, User $auth)
    {
        if (! ($auth->isAdmin() || $auth->isManager())) {
            abort(403);
        }

        $q = $this->orderRepo->getModel()::query()->with(['status', 'paymentMethod', 'user']);

        if ($statusId = $request->query('status_id')) {
            $q->where('status_id', (int) $statusId);
        }

        if ($paymentMethodId = $request->query('payment_method_id')) {
            $q->where('payment_method_id', (int) $paymentMethodId);
        }

        if ($userId = $request->query('user_id')) {
            $q->where('user_id', (int) $userId);
        }

        if ($search = $request->query('q')) {
            $q->where('order_number', 'like', '%'.$search.'%');
        }

        $perPage = min(100, (int) $request->query('per_page', 25));
        return $q->latest()->paginate($perPage);
    }

    public function show(Order $order, User $auth): Order
    {
        $this->authorize($auth, $order);
        return $order->load(['items', 'payments', 'status', 'paymentMethod', 'user']);
    }

    public function payForm(Order $order, User $auth): array
    {
        $this->authorize($auth, $order);

        $cards = UserPaymentCard::where('user_id', $order->user_id)->get()->map(fn ($card) => [
            'id'               => (int) $card->id,
            'card_number'      => $card->card_number,
            'card_holder_name' => $card->card_holder_name,
            'primary'          => (bool) $card->primary,
        ]);

        $gateways = RefPaymentMethod::all()->map(fn ($method) => [
            'id'   => (int) $method->id,
            'name' => $method->name,
            'code' => $method->code,
        ]);

        return [
            'order_id' => (int) $order->id,
            'total'    => (float) $order->amount,
            'cards'    => $cards,
            'gateways' => $gateways,
        ];
    }

    public function pay(Order $order, User $auth): JsonResponse
    {
        $this->authorize($auth, $order);

        $total  = (float) $order->amount;
        $result = $this->paymentService->chargeCustomerWithProfile($order->user_id, $total);

        if (($result['success'] ?? false) === true) {
            $payment = OrderPayment::create([
                'order_id'            => $order->id,
                'payment_method_id'   => $order->payment_method_id,
                'amount'              => $total,
                'status'              => 'succeeded',
                'transaction_id'      => $result['transaction_id'] ?? null,
                'transaction_details' => json_encode($result),
                'payment_date'        => now(),
            ]);

            $order->update(['status_id' => 3]);

            return response()->json([
                'status'     => 'succeeded',
                'payment_id' => $payment->id,
            ]);
        }

        return response()->json([
            'status'  => 'failed',
            'message' => $result['message'] ?? ($result['error'] ?? 'Payment processing failed.'),
        ], 422);
    }

    public function payments(Order $order, User $auth)
    {
        $this->authorize($auth, $order);
        return $order->payments()->with('paymentMethod')->latest()->get();
    }

    private function authorize(User $auth, Order $order): void
    {
        if ($auth->isAdmin() || $auth->isManager()) return;
        if ($order->user_id === $auth->id) return;
        abort(404);
    }
}
