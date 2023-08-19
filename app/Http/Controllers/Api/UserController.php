<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\User\StoreRequest;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(PaginateRequest $request): JsonResource
    {
        return UserResource::collection(User::getAll($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        return UserResource::make($user)
                        ->response()
                        ->setStatusCode(201);
    }

    public function show(User $user): JsonResource
    {
        return UserResource::make($user);
    }

    public function update(UpdateRequest $request, User $user): JsonResource
    {
        $user->updateRecord($request->validated());
        return UserResource::make($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(status: 204);
    }
}
