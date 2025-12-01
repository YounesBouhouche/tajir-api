<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,

            // Address Information
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,

            // Payment Information (PCI DSS Compliant)
            'payment_method' => [
                'type' => $this->payment_method_type, // 'credit_card', 'debit_card', 'paypal', 'apple_pay', etc.
                'provider' => $this->payment_provider, // 'stripe', 'square', 'paypal', etc.

                // For Credit/Debit Cards - NEVER store actual card details
                'card' => $this->when($this->payment_method_type === 'credit_card' || $this->payment_method_type === 'debit_card', [
                    'last_four' => $this->card_last_four, // Only last 4 digits
                    'brand' => $this->card_brand, // 'visa', 'mastercard', 'amex', etc.
                    'exp_month' => $this->card_exp_month,
                    'exp_year' => $this->card_exp_year,
                    'cardholder_name' => $this->cardholder_name,
                ]),

                // Gateway Tokens (PCI DSS Compliant way to reference payment methods)
                'gateway_token' => $this->payment_gateway_token, // Token from payment processor
                'customer_id' => $this->gateway_customer_id, // Customer ID in payment gateway
                'payment_method_id' => $this->gateway_payment_method_id, // Payment method ID in gateway
            ],

            // Security and Compliance
            'is_verified' => $this->is_verified ?? false,
            'verification_method' => $this->verification_method, // 'address_verification', '3d_secure', etc.
            'is_default' => $this->is_default ?? false,

            // Metadata
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
