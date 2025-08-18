<?php

namespace App\Http\Requests\Dashboard\SubManager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users, change if needed
    }

    public function rules(): array
    {
        return [
            // User details
            'user.firstname' => 'required|string|max:255',
            'user.lastname' => 'required|string|max:255',
            'user.phone' => 'nullable|string|max:20',
            'user.email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            // Company details
            'company.name' => 'required|string|max:255',
            'company.dot_number' => 'required|string|max:255',
            'company.mc_number' => 'nullable|string|max:255',
            'company.phone' => 'nullable|string|max:20',
            // Subscription details
            'sub.subscription_id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id'),
            ],
            'sub.drivers_number' => 'required|integer',
            'sub.price_per_driver' => 'required|numeric',
            //'send_payment_link' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // User validation messages
            'user.firstname.required' => 'First name is required.',
            'user.lastname.required' => 'Last name is required.',
            'user.email.required' => 'Email is required.',
            'user.email.email' => 'Email must be a valid email address.',
            'user.email.unique' => 'This email is already taken.',
            'user.phone.max' => 'Phone number cannot exceed 20 characters.',
            // Company validation messages
            'company.name.required' => 'Company name is required.',
            'company.dot_number.required' => 'DOT number is required.',
            'company.phone.max' => 'Company phone number cannot exceed 20 characters.',
            // Subscription validation messages
            'sub.subscription_id.required' => 'Subscription is required.',
            'sub.subscription_id.exists' => 'The selected subscription is invalid.',
            'sub.drivers_number.required' => 'Driver\'s number is required.',
            'sub.drivers_number.integer' => 'Driver\'s number must be an integer.',
            'sub.price_per_driver.required' => 'Price per driver is required.',
            'sub.price_per_driver.numeric' => 'Price per driver must be a numeric value.',
        ];
    }

}
