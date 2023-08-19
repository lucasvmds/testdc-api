<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'address' => 'required|string',
        ];
    }
}
