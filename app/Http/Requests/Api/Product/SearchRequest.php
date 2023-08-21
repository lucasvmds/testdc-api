<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
        ];
    }
}
