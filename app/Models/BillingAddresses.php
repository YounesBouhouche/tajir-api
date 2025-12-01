<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BillingAddresses extends Model
{
    /** @use HasFactory<\Database\Factories\BillingAddressesFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state_province',
        'postal_code',
        'country',
        'phone',
        'payment_method_type',
        'payment_gateway',
        'payment_token',
        'last_four_digits',
        'card_brand',
        'card_holder_name',
        'card_expiry_date',
        'digital_wallet_type',
        'digital_wallet_token',
        'bank_name',
        'account_holder_name',
        'account_type',
        'routing_number',
        'account_last_four',
        'is_default',
        'is_verified',
        'verified_at',
        'gateway_metadata',
    ];

    protected $casts = [
        'card_expiry_date' => 'date',
        'is_default' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'gateway_metadata' => 'array',
    ];

    protected $hidden = [
        'payment_token',
        'digital_wallet_token',
        'routing_number',
        'gateway_metadata',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name attribute
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    /**
     * Get the full address attribute
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(implode(', ', array_filter([
                $this->address_line_1,
                $this->address_line_2,
                $this->city,
                $this->state_province,
                $this->postal_code,
                $this->country
            ]))),
        );
    }

    /**
     * Get masked card number for display
     */
    protected function maskedCardNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->last_four_digits ? '**** **** **** ' . $this->last_four_digits : null,
        );
    }

    /**
     * Check if payment method is expired
     */
    public function isExpired(): bool
    {
        if (!$this->card_expiry_date) {
            return false;
        }

        return $this->card_expiry_date->isPast();
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplay(): string
    {
        return match ($this->payment_method_type) {
            'card' => $this->card_brand ? ucfirst($this->card_brand) . ' ending in ' . $this->last_four_digits : 'Credit Card',
            'paypal' => 'PayPal',
            'bank_transfer' => 'Bank Transfer',
            'apple_pay' => 'Apple Pay',
            'google_pay' => 'Google Pay',
            'samsung_pay' => 'Samsung Pay',
            default => ucfirst($this->payment_method_type),
        };
    }

    /**
     * Scope to get default billing address for user
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope to get verified billing addresses
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
