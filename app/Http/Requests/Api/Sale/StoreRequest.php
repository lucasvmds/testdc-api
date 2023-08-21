<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'products' => 'required|array',
            'products.*' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.value' => 'required|numeric',
            'products.*.quantity' => 'required|numeric',
            'installments.*' => 'nullable|array',
            'installments.*.value' => 'required|numeric',
            'installments.*.due_date' => 'required|date',
        ];
    }
}
