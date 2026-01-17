<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CashoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'phone_number' => ['required', 'string'],
            'notification_url' => ['nullable', 'url'],
            'currency' => ['nullable', 'string', 'size:3'],
            'external_reference' => ['nullable', 'string', 'max:190'],
            'shopping_cart_details' => ['nullable', 'array'],
        ];
    }
}
