<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->getDto();
        $user = User::login($data);
        if (!$user) abort(401);
        $access_token = $user->generateToken($data->remember);
        return response()->json(compact('user', 'access_token'), 201);
    }

    public function logout(): JsonResponse
    {
        User::logout();
        return response()->json(status: 204);
    }
}
