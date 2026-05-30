<?php

namespace App\Http\Resources\V1\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int) $this->id,
            'order_id'          => (int) $this->order_id,
            'payment_method_id' => $this->payment_method_id !== null ? (int) $this->payment_method_id : null,
            'amount'            => (float) $this->amount,
            'status'            => $this->status,
            'transaction_id'    => $this->transaction_id,
            'payment_date'      => optional($this->payment_date)->toIso8601String(),
            'created_at'        => optional($this->created_at)->toIso8601String(),
            'payment_method'    => $this->whenLoaded('paymentMethod', fn () => [
                'id'   => (int) $this->paymentMethod->id,
                'name' => $this->paymentMethod->name,
                'code' => $this->paymentMethod->code,
            ]),
        ];
    }
}
