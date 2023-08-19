<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Dto\Api\Auth\LoginDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'required|boolean',
        ];
    }

    public function getDto(): LoginDto
    {
        return new LoginDto(
            $this->email,
            $this->password,
            $this->remember,
        );
    }
}
