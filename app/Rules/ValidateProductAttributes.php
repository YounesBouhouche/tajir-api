<?php

namespace App\Rules;

use App\Models\ProductType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateProductAttributes implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected int $typeId;

    public function __construct($typeId)
    {
        $this->typeId = $typeId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productType = ProductType::find($this->typeId);
        if (!$productType) {
            $fail('Invalid product type.');
            return;
        }

        $schema = $productType->attributes_schema;

        if (!$schema || !is_array($schema)) {
            $fail('No attributes schema defined for this product type.');
            return;
        }

        foreach ($schema as $field => $rules) {
            if (!array_key_exists($field, $value) && in_array('required', $rules)) {
                $fail("Missing required attribute: $field.");
                return;
            }
            if (array_key_exists($field, $value)) {
                $fieldValue = $value[$field];
                $expectedType = $rules['type'] ?? null;
                if ($expectedType) {
                    if (!$this->validateType($fieldValue, $expectedType)) {
                        $fail("Attribute '$field' must be of type $expectedType.");
                        return;
                    }
                }
            }
        }
        $allowedFields = array_keys($schema);
        $providedFields = array_keys($value);
        $extraFields = array_diff($providedFields, $allowedFields);
        if (!empty($extraFields)) {
            $fail('Unknown attributes provided: ' . implode(', ', $extraFields) . '.');
        }
    }

    private function validateType($value, $type): bool
    {
        return match ($type) {
            'string' => is_string($value),
            'integer' => is_int($value),
            'boolean' => is_bool($value),
            'array' => is_array($value),
            'float' => is_float($value) || is_int($value),
            default => false,
        };
    }
}
