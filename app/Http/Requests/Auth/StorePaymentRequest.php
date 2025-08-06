<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users, change if needed
    }

    public function rules(): array
    {
        return [
            'card_name'         => 'required|string|max:255',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'card_number'       => 'required|string|max:20',
            'card_expiry_month' => 'required|string|size:2',
            'card_expiry_year'  => 'required|string|size:4',
            'card_cvv'          => 'required|string|max:4',
        ];
    }
}
