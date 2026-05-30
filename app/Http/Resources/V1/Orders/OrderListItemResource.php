<?php

namespace App\Http\Resources\V1\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int) $this->id,
            'user_id'           => (int) $this->user_id,
            'order_number'      => $this->order_number,
            'amount'            => (float) $this->amount,
            'status_id'         => $this->status_id !== null ? (int) $this->status_id : null,
            'payment_method_id' => $this->payment_method_id !== null ? (int) $this->payment_method_id : null,
            'created_at'        => optional($this->created_at)->toIso8601String(),
            'status'            => $this->whenLoaded('status', fn () => [
                'id'   => (int) $this->status->id,
                'name' => $this->status->name,
                'code' => $this->status->code,
            ]),
            'payment_method'    => $this->whenLoaded('paymentMethod', fn () => [
                'id'   => (int) $this->paymentMethod->id,
                'name' => $this->paymentMethod->name,
                'code' => $this->paymentMethod->code,
            ]),
            'user'              => $this->whenLoaded('user', fn () => [
                'id'        => (int) $this->user->id,
                'firstname' => $this->user->firstname,
                'lastname'  => $this->user->lastname,
                'email'     => $this->user->email,
            ]),
        ];
    }
}
