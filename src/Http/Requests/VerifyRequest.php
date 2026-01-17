<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
        ];
    }
}
