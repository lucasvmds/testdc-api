<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|unique:users',
            'role' => [
                'required',
                Rule::enum(UserRole::class),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(10)
                            ->mixedCase()
                            ->numbers()
                            ->symbols(),
            ],
        ];
    }
}
